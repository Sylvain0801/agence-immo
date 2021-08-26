<?php

namespace App\Controller\PrivateArea;

use App\Entity\User\Tenant;
use App\Form\PrivateArea\TenantAddEditFormType;
use App\Repository\User\TenantRepository;
use App\Security\EmailVerifier;
use App\Service\ConfigTenantTableService;
use App\Service\MailManagerService;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mime\Address;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Translation\TranslatorInterface;

/**
 * @Route("/private-area/tenant", name="private_area_tenant_")
 */
class TenantController extends AbstractController
{
    private $emailVerifier;

    public function __construct(EmailVerifier $emailVerifier)
    {
        $this->emailVerifier = $emailVerifier;
    }
    
    /**
     * @Route("/list/{sortBy}/{order}", name="list", defaults={"sortBy": "lastname", "order": "asc"})
     */
    public function list($sortBy, $order, TenantRepository $tenantRepo, ConfigTenantTableService $configTenantTableService, Request $request, PaginatorInterface $paginator): Response
    {
        $datas = $configTenantTableService->configInitDocumentTable($tenantRepo, $sortBy, $order, $this->getUser()->getId());

        $tenants = $paginator->paginate(
            $datas['table'],
            $request->query->getInt('page', 1),
            12);

        return $this->render('private_area/tenant/list.html.twig', [
            'navigationPrivate' => true,
            'active' => $datas['activeTab'],
            'headers' => $datas['headers'],
            'datas' => $tenants,
        ]);
    }

    /**
     * @Route("/view/{id}", name="view")
     */
    public function view(Tenant $tenant, Request $request): Response
    {      
        $request->getSession()->set('referer', $request->headers->get('referer'));

        return $this->render('private_area/tenant/view.html.twig', [
            'navigationPrivate' => true,
            'active' => 'tenant',
            'tenant' => $tenant,
        ]);
    }

    /**
     * @Route("/new", name="new")
     */
    public function new(Request $request, UserPasswordHasherInterface $userPasswordHasher, MailManagerService $mailManager, TranslatorInterface $translator): Response
    {      
        $tenant = new Tenant();
        $form = $this->createForm(TenantAddEditFormType::class, $tenant);
        $formDatas = $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $password = $this->generate_password();
            $tenant
                ->setPassword($userPasswordHasher->hashPassword($tenant, $password))
                ->setRoles(['ROLE_TENANT']);

            $em = $this->getDoctrine()->getManager();
            $em->persist($tenant);
            $em->flush();

            $this->emailVerifier->sendEmailConfirmation('app_verify_email', $tenant,
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
                'private_area/tenant/email',
                $context
            );

            $this->addFlash('message_alert', [
                'text' => $translator->trans('The tenant account has been created successfully'), 
                'style' => 'success'
            ]);

            return $this->redirectToRoute('private_area_tenant_list');
        }

        return $this->render('private_area/tenant/new.html.twig', [
            'navigationPrivate' => true,
            'active' => 'tenant',
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
