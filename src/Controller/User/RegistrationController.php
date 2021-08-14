<?php

namespace App\Controller\User;

use App\Entity\User\PrivateOwner;
use App\Form\User\RegistrationFormType;
use App\Security\EmailVerifier;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mime\Address;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use SymfonyCasts\Bundle\VerifyEmail\Exception\VerifyEmailExceptionInterface;

class RegistrationController extends AbstractController
{
    private $emailVerifier;

    public function __construct(EmailVerifier $emailVerifier)
    {
        $this->emailVerifier = $emailVerifier;
    }

    /**
     * @Route("/register/private-owner", name="app_register_private_owner")
     */
    public function register(Request $request, UserPasswordHasherInterface $userPasswordHasher): Response
    {

        $user = new PrivateOwner();
        $form = $this->createForm(RegistrationFormType::class, $user);
        $formData = $form->handleRequest($request);

        if (count($formData->getErrors()) > 0) {
            $errorMessage = $formData->getErrors()[0]->getMessage();
            $this->addFlash('verify_email_error', $errorMessage);
        }
        
        if ($form->isSubmitted() && $form->isValid()) {
            // encode the plain password
            $user->setPassword($userPasswordHasher->hashPassword($user, $formData->get('plainPassword')->getData()));

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($user);
            $entityManager->flush();
            
            
            // generate a signed url and email it to the user
            $this->emailVerifier->sendEmailConfirmation('app_verify_email', $user,
                (new TemplatedEmail())
                    ->from(new Address('agence-immobiliere@no-reply.com', 'Agence Immobilière'))
                    ->to($user->getEmail())
                    ->subject('Please Confirm your Email')
                    ->htmlTemplate('registration/confirmation_email.html.twig')
            );
            // do anything else you need here, like send an email
            $request->getSession()->set('email', $user->getEmail());

            return $this->redirectToRoute('payment');
        }

        return $this->render('registration/register.html.twig', [
            'registrationForm' => $form->createView(),
        ]);
    }

    /**
     * @Route("/verify/email", name="app_verify_email")
     */
    public function verifyUserEmail(Request $request): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        // validate email confirmation link, sets User::isVerified=true and persists
        try {
            $this->emailVerifier->handleEmailConfirmation($request, $this->getUser());
        } catch (VerifyEmailExceptionInterface $exception) {
            $this->addFlash('verify_email_error', $exception->getReason());

            return $this->redirectToRoute('app_register_private_owner');
        }

        // @TODO Change the redirect on success and handle or remove the flash message in your templates
        $this->addFlash('success', 'Your email address has been verified.');

        return $this->redirectToRoute('home');
    }
}
