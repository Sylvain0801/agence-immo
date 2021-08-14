<?php

namespace App\Controller\PrivateArea;

use App\Entity\Property\Offer;
use App\Entity\Property\Property;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/private-area", name="private_area_")
 */
class MainController extends AbstractController
{
    /**
     * @Route("/", name="home")
     */
    public function index(): Response
    {
        return $this->render('private_area/index.html.twig', [
            'navigationPrivate' => true,
        ]);
    }

    /**
     * @Route("/list/{entity}/{sort}/{order}", name="list", defaults={"sort": "id", "order": "asc"})
     */
    public function list($entity, $sort, $order): Response
    {
        switch ($entity) {
            case 'offer':
                $datas = $this->getDoctrine()->getRepository(Offer::class)->findAll();
                $active = 'offer';
                break;
            case 'property':
                $datas = $this->getDoctrine()->getRepository(Property::class)->findListSortBycriteria($sort, $order);
                $active = 'property';
                $headers = [
                    'id' => 'Id',
                    'transaction_type' => 'transaction',
                    'city' => 'city',
                    'property_type' => 'type',
                    'price' => 'price',
                    'area' => 'area',
                    'rooms' => 'rooms',
                    'energy' => 'energy',
                    'ges' => 'GHG',
                ];
                break;
            default:
                break;
        }
        return $this->render('private_area/list.html.twig', [
            'navigationPrivate' => true,
            'active' => $active,
            'headers' => $headers,
            'datas' => $datas
        ]);
    }
}
