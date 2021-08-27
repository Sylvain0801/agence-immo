<?php

namespace App\Controller\PrivateArea;

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
}
