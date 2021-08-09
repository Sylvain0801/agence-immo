<?php

namespace App\Controller;

use App\Repository\Faq\CategoryFaqRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class FaqController extends AbstractController
{
    /**
     * @Route("/faq", name="faq")
     */
    public function index(CategoryFaqRepository $categoryFaqRepository): Response
    {
        $categories = $categoryFaqRepository->findAll();

        return $this->render('faq/index.html.twig', [
            'categories' => $categories,
            'active' => 'faq'
        ]);
    }
}
