<?php

namespace App\Controller\User;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Address;
use Symfony\Component\Mime\Email;
use Symfony\Component\Routing\Annotation\Route;

class PaymentController extends AbstractController
{
    /**
     * @Route("/payment", name="payment")
     */
    public function index(): Response
    {
        $price = 600;
        $intent =  json_decode($this->intention($price)->getContent());
        return $this->render('user/payment/index.html.twig', [
            'intent' => $intent
        ]);
    }

    /**
     * @Route("/payment/intention/{price}", name="payment_intention")
     */
    public function intention($price): JsonResponse
    {
        
        // Appel de l'autoloader pour avoir accès à stripe
        require_once dirname(__DIR__, 3).DIRECTORY_SEPARATOR.'vendor'.DIRECTORY_SEPARATOR.'autoload.php';

        // Instanciation de stripe avec la clé privée
        \Stripe\Stripe::setApiKey('sk_test_51IhWOpHIGsc4DWz6u0IVWfOAG33fe1e8rvZbcyWe3QsiA1fgDcOStd4ynEWFRzkRJuNV8p2oVf8ll1ZA5kMKwkBZ00BcaFd4k7');

        // Création de l'intention de paiement et stockage dans $intent
        $intent = \Stripe\PaymentIntent::create([
                'amount' => (int)$price * 100, // Le prix doit être transmis en centimes
                'currency' => 'eur',
        ]);
        return new JsonResponse($intent);
    }

    /**
     * @Route("/payment/success", name="payment_success")
     */
    public function success(Request $request, MailerInterface $mailer): RedirectResponse
    {
            $email = (new Email())
                ->from(new Address('agence-immobiliere@no-reply.com', 'Agence Immobilière'))
                ->to($request->getSession()->get('email'))
                //->cc('cc@example.com')
                //->bcc('bcc@example.com')
                //->replyTo('fabien@example.com')
                //->priority(Email::PRIORITY_HIGH)
                ->subject('Time for Symfony Mailer!')
                ->text('Sending emails is fun again!')
                ->html('<p>See Twig integration for better HTML integration!</p>');
    
            dd($email);
            $mailer->send($email);
            return $this->redirectToRoute('payment');
    }


}
