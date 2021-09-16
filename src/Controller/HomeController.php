<?php

namespace App\Controller;

use App\Repository\Property\OfferRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


class HomeController extends AbstractController
{
    /**
     * @Route("/", name="home")
     */
    public function index(OfferRepository $offerRepository, Request $request): Response
    {
        // Met par défaut la langue en français
        if (!$request->getSession()->get('_locale')) {
            $request->getSession()->set('_locale', 'fr');
            return $this->redirectToRoute('home');
        }

        $offers = $offerRepository->findArrayAllDatas();
        $limitResults = 11;
        
        return $this->render('home/index.html.twig', [
            'offers' => $offers,
            'limit_results' => $limitResults
        ]);
    }
}
