<?php

namespace App\Controller\PrivateArea;

use App\Entity\User\Owner;
use App\Form\PrivateArea\OwnerAddEditFormType;
use App\Repository\User\OwnerRepository;
use App\Security\EmailVerifier;
use App\Service\ConfigOwnerTableService;
use App\Service\MailManagerService;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mime\Address;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

/**
 * @Route("/private-area/owner", name="private_area_owner_")
 */
class OwnerController extends AbstractController
{
    private $emailVerifier;

    public function __construct(EmailVerifier $emailVerifier)
    {
        $this->emailVerifier = $emailVerifier;
    }
    
    /**
     * @Route("/list/{sortBy}/{order}", name="list", defaults={"sortBy": "lastname", "order": "asc"})
     */
    public function list($sortBy, $order, OwnerRepository $ownerRepo, ConfigOwnerTableService $configOwnerTableService, Request $request, PaginatorInterface $paginator): Response
    {
        $datas = $configOwnerTableService->configInitDocumentTable($ownerRepo, $sortBy, $order, $this->getUser()->getId());

        $owners = $paginator->paginate(
            $datas['table'],
            $request->query->getInt('page', 1),
            12);

        return $this->render('private_area/owner/list.html.twig', [
            'navigationPrivate' => true,
            'active' => $datas['activeTab'],
            'headers' => $datas['headers'],
            'datas' => $owners,
        ]);
    }

    /**
     * @Route("/view/{id}", name="view")
     */
    public function view(Owner $owner, Request $request): Response
    {      
        $request->getSession()->set('referer', $request->headers->get('referer'));

        return $this->render('private_area/owner/view.html.twig', [
            'navigationPrivate' => true,
            'active' => 'owner',
            'owner' => $owner,
        ]);
    }

    /**
     * @Route("/new", name="new")
     */
    public function new(Request $request, UserPasswordEncoderInterface $passwordEncoder, MailManagerService $mailManager, TranslatorInterface $translator): Response
    {      
        $owner = new Owner();
        $form = $this->createForm(OwnerAddEditFormType::class, $owner);
        $formDatas = $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $password = $this->generate_password();
            $owner
                ->setPassword($passwordEncoder->encodePassword($owner, $password))
                ->setRoles(['ROLE_OWNER'])
                ->setAgent($this->getUser());

            $em = $this->getDoctrine()->getManager();
            $em->persist($owner);
            $em->flush();

            $this->emailVerifier->sendEmailConfirmation('app_verify_email', $owner,
            (new TemplatedEmail())
                ->from(new Address('agence-immobiliere@no-reply.com', 'Agence Immobilière'))
                ->to($formDatas->get('email')->getData())
                ->subject('Please Confirm your Email')
                ->htmlTemplate('registration/confirmation_email.html.twig')
            );
            
            $subject = $translator->trans('Your account has been successfully created');
            $context = [
                'mail' => $this->getUser()->getEmail(),
                'subject' => $subject,
                'password' => $password,
                'username' => $formDatas->get('email')->getData()
            ];

            $mailManager->create(
                $this->getUser()->getEmail(),
                $formDatas->get('email')->getData(),
                $subject,
                'private_area/owner/email',
                $context
            );

            $this->addFlash('message_alert', [
                'text' => $translator->trans('The owner account has been created successfully'), 
                'style' => 'success'
            ]);

            return $this->redirectToRoute('private_area_owner_list');
        }

        return $this->render('private_area/owner/new.html.twig', [
            'navigationPrivate' => true,
            'active' => 'owner',
            'form' => $form->createView(),
        ]);
    }

    private function generate_password(int $length = 10)
    {
        $chars =  'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz'.
                  '0123456789`-=~!@#$%^&*()_+,./<>?;:[]{}\|';
      
        $str = '';
        $max = strlen($chars) - 1;
      
        for ($i=0; $i < $length; $i++)
          $str .= $chars[random_int(0, $max)];
      
        return $str;
    }
}
