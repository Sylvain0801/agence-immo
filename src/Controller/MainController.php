<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MainController extends AbstractController
{
    /**
     * @Route("/change-locale/{locale}", name="change_locale")
     */
    public function changeLocale($locale, Request $request)
    {
        // stocke la langue demandée dans la session
        $request->getSession()->set('_locale', $locale);

        //redirige vers la page précédente
        return $this->redirect($request->headers->get('referer'));
    }

    /**
     * @Route("/referer", name="referer")
     */
    public function referer(Request $request): RedirectResponse
    {
        //redirige vers la page précédente
        if ($request->getSession()->get('referer')) {
            return $this->redirect($request->getSession()->get('referer'));
        }
    }
}
