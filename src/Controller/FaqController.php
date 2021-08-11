<?php

namespace App\Controller;

use App\Entity\Faq\Faq;
use App\Form\Faq\ContactFormType;
use App\Repository\Faq\CategoryFaqRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Translation\TranslatorInterface;

/**
 * @Route("/faq", name="faq_")
 */
class FaqController extends AbstractController
{
    /**
     * @Route("/home", name="home")
     */
    public function index(CategoryFaqRepository $categoryFaqRepository): Response
    {
        $categories = $categoryFaqRepository->findAll();

        return $this->render('faq/index.html.twig', [
            'categories' => $categories,
            'active' => 'faq'
        ]);
    }

    /**
     * @Route("/contact", name="contact")
     */
    public function contact(Request $request, TranslatorInterface $translator): Response
    {
        $faq = new Faq();
        $form = $this->createForm(ContactFormType::class, $faq);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $em = $this->getDoctrine()->getManager();
            $em->persist($faq);
            $em->flush();

            $message = $translator->trans('your question has been sent successfully');
            $this->addFlash('message_popup', $message);

            return $this->redirectToRoute('faq_contact');
        }

        return $this->render('faq/contact.html.twig', [
            'form' => $form->createView(),
            'active' => 'faq'
        ]);
    }
}
