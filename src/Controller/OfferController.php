<?php

namespace App\Controller;

use App\Entity\Property\Offer;
use App\Repository\Property\OfferRepository;
use App\Repository\Property\PropertyTypeRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/offer", name="offer_")
 */
class OfferController extends AbstractController
{
    /**
     * @Route("/", name="home")
     */
    public function index(PropertyTypeRepository $propertyTypeRepository, OfferRepository $offerRepository, Request $request, PaginatorInterface $paginator): Response
    {   
        $propertyTypes = $propertyTypeRepository->findAll();
        $data = $offerRepository->findAllArray();

        // Réinitialise tous les filtres du formulaire
        $fields = [
            'sell' => null, 
            'sellPriceMin' => null, 
            'sellPriceMax' => null,
            'rent' => null, 
            'rentPriceMin' => null, 
            'rentPriceMax' => null,
            'rooms' => null, 
            'type' => null, 
            'furnished' => null, 
            'cityCoord' => null, 
            'radius' => null,
            'cityName' => null
        ];

        $request->getSession()->remove('_formFields');

        $offers = $paginator->paginate(
            $data,
            $request->query->getInt('page', 1),
            10
        );

        return $this->render('offer/index.html.twig', [
            'offers' => $offers,
            'active' => 'offers',
            'fields' => $fields,
            'propertyTypes' => $propertyTypes
        ]);
    }

    /**
     * @Route("/search", name="search")
     */
    public function search(PropertyTypeRepository $propertyTypeRepository, OfferRepository $offerRepository, Request $request, PaginatorInterface $paginator): Response
    {
        $propertyTypes = $propertyTypeRepository->findAll();
        // Récupère toutes les valeurs du formulaire
        // Vérifie si des critères sont enregistrés en session
        if (count($request->request) > 0) {
            $request->get("sell") !== null && !empty($request->get("sell")) ? $sell = $request->get("sell") : $sell = null;
            $request->get("sell_price_min") !== null && !empty($request->get("sell_price_min")) ? $sellPriceMin = $request->get("sell_price_min") : $sellPriceMin = null;
            $request->get("sell_price_max") !== null && !empty($request->get("sell_price_max")) ? $sellPriceMax = $request->get("sell_price_max") : $sellPriceMax = null;
            $request->get("rent") !== null && !empty($request->get("rent")) ? $rent = $request->get("rent") : $rent = null;
            $request->get("rent_price_min") !== null && !empty($request->get("rent_price_min")) ? $rentPriceMin = $request->get("rent_price_min") : $rentPriceMin = null;
            $request->get("rent_price_max") !== null && !empty($request->get("rent_price_max")) ? $rentPriceMax = $request->get("rent_price_max") : $rentPriceMax = null;
            $request->get("rooms") !== null && !empty($request->get("rooms")) ? $rooms = $request->get("rooms") : $rooms = null;
            $request->get("type") !== null && !empty($request->get("type")) ? $type = $request->get("type") : $type = null;
            $request->get("furnished") !== null && !empty($request->get("furnished")) ? $furnished = $request->get("furnished") : $furnished = null;
            $request->get("city") !== null && !empty($request->get("city")) ? $cityName = $request->get("city") : $cityName = null;
            $request->get("coord") !== null && !empty($request->get("coord")) ? $cityCoord = $request->get("coord") : $cityCoord = null;
            $request->get("radius") !== null && !empty($request->get("radius")) ? $radius = (int)$request->get("radius") : $radius = 5;
        } else {
            $sessionFields = $request->getSession()->get('_formFields');
            if ($sessionFields) {
                $sell = $sessionFields['sell']; 
                $sellPriceMin = $sessionFields['sellPriceMin']; 
                $sellPriceMax = $sessionFields['sellPriceMax'];
                $rent = $sessionFields['rent']; 
                $rentPriceMin = $sessionFields['rentPriceMin']; 
                $rentPriceMax = $sessionFields['rentPriceMax'];
                $rooms = $sessionFields['rooms']; 
                $type = $sessionFields['type']; 
                $furnished = $sessionFields['furnished']; 
                $cityCoord = $sessionFields['cityCoord']; 
                $radius = $sessionFields['radius'];
                $cityName = $sessionFields['cityName'];
            } else {
                throw new NotFoundHttpException('Une erreur s\'est produite, veuillez réessayer plus tard.');
            }
        }
        
        // Récupére les coordonnées de la ville de recherche et affiche un message d'erreur si la ville est mal renseignée
        if ($cityCoord) {
            $cityCoordOrigin = implode(',', array_reverse(explode(',', $cityCoord)));
        } else {
            $this->addFlash('message_alert', ['text' => "Le nom de la ville est incorrect, veuillez choisir une ville dans la liste.", 'style' => 'danger']);
            return $this->redirectToRoute('offer_home', [], Response::HTTP_SEE_OTHER);
        }

        // Crée un tableau avec la liste des filtres et leur valeur
        $fields = [
            'sell' => $sell, 
            'sellPriceMin' => $sellPriceMin, 
            'sellPriceMax' => $sellPriceMax,
            'rent' => $rent, 
            'rentPriceMin' => $rentPriceMin, 
            'rentPriceMax' => $rentPriceMax,
            'rooms' => $rooms, 
            'type' => $type, 
            'furnished' => $furnished, 
            'cityCoord' => $cityCoord, 
            'radius' => $radius,
            'cityName' => $cityName
        ];
        $request->getSession()->set('_formFields', $fields);
        
        $offerCount = null;

        // Récupère la liste des villes du repo Offer avec leurs coordonnées
        $coordCities = $offerRepository->findOfferCitiesCoord();
        $temp = [];
        foreach ($coordCities as $key => $coord) {
            $temp[$key] = $coord;
        }
        $citiesGroup = array_chunk($temp, 25);

        // Boucle pour récupèrer toutes les distances
        $distances =[];
        for ($i = 0; $i < count($citiesGroup); $i++) { 
            
            // Crée une chaine de caractères avec les coordonnées des villes
            $temp = [];
            foreach($citiesGroup[$i] as $key => $city) {
                $temp[$key] = $city['coordinates'];
            }
            $citiesGroupCoord = implode('|', $temp);
            // Récupére les distances via l'api
            $datas = json_decode(file_get_contents("https://maps.googleapis.com/maps/api/distancematrix/json?units=metric&origins=$cityCoordOrigin&destinations=$citiesGroupCoord&key=AIzaSyB1Q8l6-m7A9Rb6v_WQGB8LOBXJboI8O7o"), true);
            // Crée un tableau avec l'id des villes et les distances
            foreach ($datas['rows'][0]['elements'] as $key => $data) {
                if ($data['distance']['value'] < $radius * 1000) {
                    $distances[$coordCities[25 * $i + $key]['id']] = $data['distance']['value'];
                }
            }
        }
        if (count($distances) > 0) {
            $data = $offerRepository->searchOfferByCriteria($distances, $sell, $sellPriceMin, $sellPriceMax, $rent, $rentPriceMin, $rentPriceMax, $rooms, $type, $furnished);
            $offerCount = count($data);
        } else {
            $data = null;
        }
        if ($data) {
            $offers = $paginator->paginate(
            $data,
            $request->query->getInt('page', 1),
            10);
        } else {
            $offers = $data;
        }
    
        return $this->render('offer/search_results.html.twig', [
            'offers' => $offers,
            'offerCount' => $offerCount,
            'fields' => $fields,
            'propertyTypes' => $propertyTypes,
            'active' => 'offers'
        ]);
    }

    /**
     * @Route("/favorite/{favoriteList}", name="favorite", defaults={"favoriteList": null})
     */
    public function favorite($favoriteList, OfferRepository $offerRepository, Request $request, PaginatorInterface $paginator): Response
    {
        $liste = explode(',', urldecode($favoriteList));
        if ($liste !== null && count($liste) > 0) {
            $data = $offerRepository->findFavorites($liste);
        }
        if ($data) {
            $offers = $paginator->paginate(
                $data,
                $request->query->getInt('page', 1),
                10
            );
            $offerCount = count($data);
        } else {
            $offers = null;
            $offerCount = null;
        }


        return $this->render('offer/favorite.html.twig', [
            'offers' => $offers,
            'offerCount' => $offerCount
        ]);
    }

    /**
     * @Route("/view/{slug}", name="view")
     */
    public function view($slug, Offer $offer, Request $request): Response
    {
        $request->getSession()->set('referer', $request->headers->get('referer'));

        $offer = $this->getDoctrine()->getRepository(Offer::class)->findOneBy(['slug' => $slug]);

        $letters = ['A', 'B', 'C', 'D', 'E', 'F', 'G'];

        return $this->render('offer/view.html.twig', [
            'offer' => $offer,
            'active' => 'offers',
            'letters' => $letters
        ]);
    }
}
