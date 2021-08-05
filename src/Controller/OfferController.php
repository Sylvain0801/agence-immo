<?php

namespace App\Controller;

use App\Form\SearchFormType;
use App\Repository\OfferRepository;
use App\Repository\PropertyRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class OfferController extends AbstractController
{
    /**
     * @Route("/offer", name="offer")
     */
    public function index(OfferRepository $offerRepository, PropertyRepository $propertyRepository, Request $request): Response
    {
        
        $offers = $offerRepository->findAllArray();
        $form = $this->createForm(SearchFormType::class);
        // $search = $form->handleRequest($request);

        return $this->render('offer/index.html.twig', [
            'offers' => $offers,
            'form' => $form->createView(),
            'active' => 'offers'
        ]);
    }
}
