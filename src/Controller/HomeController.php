<?php

namespace App\Controller;

use App\Repository\Property\OfferRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


class HomeController extends AbstractController
{
    /**
     * @Route("/", name="home")
     */
    public function index(OfferRepository $offerRepository): Response
    {
        $offers = $offerRepository->findAllArray(11);
        return $this->render('home/index.html.twig', [
            'offers' => $offers,
        ]);
    }
}
