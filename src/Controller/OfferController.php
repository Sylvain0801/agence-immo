<?php

namespace App\Controller;

use App\Form\SearchFormType;
use App\Repository\OfferRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/offer", name="offer_")
 */
class OfferController extends AbstractController
{
    /**
     * @Route("/", name="home")
     */
    public function index(OfferRepository $offerRepository, Request $request, PaginatorInterface $paginator): Response
    {
        $data = $offerRepository->findAllArray();
        $offerCount = null;
        $form = $this->createForm(SearchFormType::class);
        $search = $form->handleRequest($request);


        if ($form-> isSubmitted() && $form->isValid()) {
            // Récupère la liste des villes du repo Offer avec leurs coordonnées
            $coordCities = $offerRepository->findOfferCitiesCoord();
            $temp = [];
            foreach ($coordCities as $key => $coord) {
                $temp[$key] = $coord;
            }
            $citiesGroup = array_chunk($temp, 25);

            // Récupére les coordonnées de la ville de recherche
            if ($search->get("coord")->getData() !== null) {
                $cityCoord = implode(',', array_reverse(explode(',', $search->get("coord")->getData())));
            } else {
                return $this->redirectToRoute('offer_home', [], Response::HTTP_SEE_OTHER);
            }

            // Récupére le rayon de recherche
            $search->get("radius")->getData() !== null ? $radius = (int)$search->get("radius")->getData() : $radius = 5;

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
                $datas = json_decode(file_get_contents("https://maps.googleapis.com/maps/api/distancematrix/json?units=metric&origins=$cityCoord&destinations=$citiesGroupCoord&key=AIzaSyB1Q8l6-m7A9Rb6v_WQGB8LOBXJboI8O7o"), true);
                
                // Crée un tableau avec l'id des villes et les distances
                foreach ($datas['rows'][0]['elements'] as $key => $data) {
                    if ($data['distance']['value'] < $radius * 1000) {
                        $distances[$coordCities[25 * $i + $key]['id']] = $data['distance']['value'];
                    }
                }
            }
            $type = null;
            if ($search->get("type")->getData() !== null) $type = $search->get("type")->getData()->getId();
            
            if (count($distances) > 0) {
                $data = $offerRepository->searchOfferByCriteria(
                    $distances,
                    $search->get("sell")->getData(),
                    $search->get("sell_price_min")->getData(),
                    $search->get("sell_price_max")->getData(),
                    $search->get("rent")->getData(),
                    $search->get("rent_price_min")->getData(),
                    $search->get("rent_price_max")->getData(),
                    $search->get("rooms")->getData(),
                    $type,
                    $search->get("furnished")->getData()
                );
                $offerCount = count($data);
            } else {
                $data = null;
            }
        }

        $offers = $paginator->paginate(
            $data,
            $request->query->getInt('page', 1),
            10
        );

        return $this->render('offer/index.html.twig', [
            'offers' => $offers,
            'offerCount' => $offerCount,
            'form' => $form->createView(),
            'active' => 'offers'
        ]);
    }

}
