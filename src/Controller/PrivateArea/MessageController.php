<?php

namespace App\Controller\PrivateArea;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MessageController extends AbstractController
{
    /**
     * @Route("/private/area/message", name="private_area_message")
     */
    public function index(): Response
    {
        return $this->render('private_area/message/index.html.twig', [
            'controller_name' => 'MessageController',
        ]);
    }
}
