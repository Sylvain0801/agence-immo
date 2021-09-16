<?php

namespace App\Controller\PrivateArea;

use App\Form\User\UserEditProfileFormType;
use App\Repository\CalendarRepository;
use App\Repository\Document\DocHasSeenRepository;
use App\Repository\Message\UserHasMessageReadRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Translation\TranslatorInterface;

/**
 * @Route("/private-area", name="private_area_")
 */
class MainController extends AbstractController
{
    /**
     * @Route("/", name="home")
     */
    public function index(UserHasMessageReadRepository $mgsReadRepo, DocHasSeenRepository $docHasSeenRepo, CalendarRepository $calendarRepo): Response
    {
        $messages = $mgsReadRepo->findBy(['recipient' => $this->getUser()->getId(), 'is_read' => false]);
        $documents = $docHasSeenRepo->findBy(['user' => $this->getUser(), 'has_seen' => false]);
        $reminders = $calendarRepo->getAllRemindersForNextTwoMonths($this->getUser());

        return $this->render('private_area/index.html.twig', [
            'navigationPrivate' => true,
            'active' => 'dashboard',
            'messages' => $messages,
            'documents' => $documents,
            'reminders' => $reminders
        ]);
    }

    /**
     * @Route("/edit-profil", name="edit_profil")
     */
    public function editProfil(Request $request, TranslatorInterface $translator): Response
    {
        $user = $this->getUser();
        $form = $this->createForm(UserEditProfileFormType::class, $user);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {
            
            $em = $this->getDoctrine()->getManager();
            $em->persist($user);
            $em->flush();

            $this->addFlash('message_alert', [
                'text' => $translator->trans('Your profile has been modified successfully'), 
                'style' => 'success'
            ]);

            return $this->redirectToRoute('private_area_home');
        }

        return $this->render('private_area/edit_profil.html.twig', [
                    'navigationPrivate' => true,
                    'active' => 'dashboard',
                    'form' => $form->createView(),
                ]);
    }
}
