<?php

namespace App\Controller\Page;

use App\Form\Page\Contact\ContactAgencyFormType;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Address;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Translation\TranslatorInterface;

class ContactController extends AbstractController
{
    /**
     * @Route("/page/contact/agency/{reason}", name="contact_home", defaults={"reason": null})
     */
    public function index($reason, Request $request, TranslatorInterface $translator, MailerInterface $mailer): Response
    {
        if ($reason) {
            $reasons = [
                "La gestion d’un logement" => 0
            ];
            $placeholder = false;
        } else {
            $reasons = [
                "L’achat d’un bien" => 0,
                "La vente / l’estimation d’un bien" => 1,
                "La gestion d’une copropriété" => 2,
                "L’investissement locatif" => 3,
                "Une réclamation" => 4,
                "La gestion d’un logement" => 5
            ];
            $placeholder = $translator->trans('-- Choose a reason --');
        }
       
        $form = $this->createForm(ContactAgencyFormType::class, ['reasons' => $reasons, 'placeholder' => $placeholder]);
        $formData = $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $subject = array_keys($reasons)[$formData->get('reason')->getData()];
            $email = (new TemplatedEmail())
                ->from(new Address('agence-immobiliere@no-reply.com', 'Agence Immobilière'))
                ->to('agence-immobiliere@no-reply.com')
                ->subject($subject)
                ->htmlTemplate('page/contact/email.html.twig')
                ->context([
                    'firstname' => $formData->get('firstname')->getData(),
                    'lastname' => $formData->get('lastname')->getData(),
                    'mail' => $formData->get('email')->getData(),
                    'phone_number' => $formData->get('phone_number')->getData(),
                    'reason' => $subject,
                    'message' => $formData->get('message')->getData()
                ]);
    
            $mailer->send($email);

            $message = $translator->trans('your message has been sent successfully');
            $this->addFlash('message_popup', $message);

            return $this->redirectToRoute('contact_home');
        }

        return $this->render('page/contact/index.html.twig', [
            'active' => 'contact',
            'form' => $form->createView()
        ]);
    }
}
