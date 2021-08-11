<?php

namespace App\Controller\Page;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PrivateOwnerController extends AbstractController
{
    /**
     * @Route("/page/private/owner/information", name="page_private_owner_information")
     */
    public function information(): Response
    {
        return $this->render('page/private_owner/information.html.twig', [
            'active' => 'private_owner'
        ]);
    }
}
