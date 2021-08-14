<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class IconsController extends AbstractController
{
    /**
     * @Route("/icons", name="icons")
     */
    public function index(): Response
    {
        require_once(dirname(__FILE__, 3) . str_replace('\\', DIRECTORY_SEPARATOR, '\public\assets\style\icons\iconsData.php'));

        return $this->render('icons/index.html.twig', [
            'icons' => $icons,
        ]);
    }
}
