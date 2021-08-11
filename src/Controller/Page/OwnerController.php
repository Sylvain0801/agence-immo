<?php

namespace App\Controller\Page;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class OwnerController extends AbstractController
{
    /**
     * @Route("/page/owner/information", name="page_owner_information")
     */
    public function information(): Response
    {
        return $this->render('page/owner/information.html.twig', [
            'active' => 'owner'
        ]);
    }
}
