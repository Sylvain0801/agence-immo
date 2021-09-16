<?php

namespace App\Controller\PrivateArea;

use App\Entity\Message\Message;
use App\Entity\Message\UserHasMessageRead;
use App\Entity\User\User;
use App\Form\PrivateArea\MessageAnswerFormType;
use App\Repository\Message\MessageRepository;
use App\Repository\Message\UserHasMessageReadRepository;
use App\Service\ConfigMessageTableService;
use App\Service\MailManagerService;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Translation\TranslatorInterface;

/**
 * @Route("/private-area/message", name="private_area_message_")
 */
class MessageController extends AbstractController
{
    
    /**
     * @Route("/list/{sortBy}/{order}", name="list", defaults={"sortBy": "is_read", "order": "asc"})
     */
    public function list($sortBy, $order, UserHasMessageReadRepository $msgReadRepo, ConfigMessageTableService $configMessageTableService, Request $request, PaginatorInterface $paginator): Response
    {
        $datas = $configMessageTableService->configInitMessageTable($msgReadRepo, $sortBy, $order, $this->getUser()->getId());

        $messages = $paginator->paginate(
            $datas['table'],
            $request->query->getInt('page', 1),
            10);

        return $this->render('private_area/message/list.html.twig', [
            'navigationPrivate' => true,
            'active' => $datas['activeTab'],
            'headers' => $datas['headers'],
            'datas' => $messages,
        ]);
    }

    /**
     * @Route("/read/{id}", name="read")
     */
    public function read(UserHasMessageRead $msgRead): Response
    {
        $msgRead->getIsRead() ? $msgRead->setIsRead(false) : $msgRead->setIsRead(true);
        $em = $this->getDoctrine()->getManager();
        $em->persist($msgRead);
        $em->flush();

        return new Response('true');
    }

    
    public function countMessageNotRead(UserHasMessageReadRepository $msgReadRepo): Response
    {
        $nbMessageNotRead = count($msgReadRepo->countMsgNotReadByUser($this->getUser()->getId()));

        return $this->render('private_area/_message_not_read.html.twig', [
            'nbMessageNotRead' => $nbMessageNotRead
        ]);
    }

    /**
     * @Route("/view/{id}", name="view")
     */
    public function view(UserHasMessageRead $msgRead): Response
    {
        $msgRead->setIsRead(true);
        $em = $this->getDoctrine()->getManager();
        $em->persist($msgRead);
        $em->flush();

        return $this->render('private_area/message/view.html.twig', [
            'navigationPrivate' => true,
            'active' => 'message',
            'message' => $msgRead,
        ]);
    }

    /**
     * @Route("/sent-view/{id}", name="sent_view")
     */
    public function sentView(Message $msg): Response
    {
        return $this->render('private_area/message/sent_view.html.twig', [
            'navigationPrivate' => true,
            'active' => 'message',
            'message' => $msg,
        ]);
    }

    /**
     * @Route("/reply/{id}", name="reply")
     */
    public function reply(UserHasMessageRead $msgRead, Request $request, TranslatorInterface $translator): Response
    {
        $msgRead->setIsRead(true);
        $em = $this->getDoctrine()->getManager();
        $em->persist($msgRead);
        $em->flush();
        
        $message = new Message();
        $form = $this->createForm(MessageAnswerFormType::class, $message);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {


            $message->setSender($this->getUser());
            $em->persist($message);

            $newMsgRead = (new UserHasMessageRead())
                    ->setMessage($message)
                    ->setRecipient($msgRead->getMessage()->getSender());
            $em->persist($newMsgRead);
           
            $em->flush();

            $this->addFlash('message_alert', [
                'text' => $translator->trans('your message has been sent successfully'), 
                'style' => 'success'
            ]);

            return $this->redirectToRoute('private_area_message_list');
        }

        return $this->render('private_area/message/reply.html.twig', [
            'navigationPrivate' => true,
            'active' => 'message',
            'form' => $form->createView(),
            'msgRead' => $msgRead
        ]);
    }

    /**
     * @Route("/contact-agent", name="contact_agent")
     */
    public function contactAgent(Request $request, MailManagerService $mailManager, TranslatorInterface $translator): Response
    {
        $this->denyAccessUnlessGranted('ROLE_OWNER');
        $agent = $this->getUser()->getAgent();
        
        $message = new Message();
        $form = $this->createForm(MessageAnswerFormType::class, $message);
        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();

            $message->setSender($this->getUser());
            $em->persist($message);

            $msgRead = (new UserHasMessageRead())->setMessage($message)->setRecipient($agent);   
            $em->persist($msgRead);
            $em->flush();

            $subject = $translator->trans('A new message in your private area');
            $content = $translator->trans('Go to the message tab of your private area, you have received a new message.');

            $context = [
                'mail' => $this->getUser()->getEmail(),
                'firstname' => $this->getUser()->getFirstname(),
                'lastname' => $this->getUser()->getLastname(),
                'subject' => $subject,
                'message' => $content
            ];
            $mailManager->create(
                $this->getUser()->getEmail(),
                $agent->getEmail(),
                $subject,
                'private_area/message/email',
                $context
            );

            $this->addFlash('message_alert', [
                'text' => $translator->trans('your message has been sent successfully'), 
                'style' => 'success'
            ]);

            return $this->redirectToRoute('private_area_message_list');
        }

        return $this->render('private_area/message/contact_agent.html.twig', [
            'navigationPrivate' => true,
            'active' => 'message',
            'form' => $form->createView(),
            'agent' => $agent
        ]);
    }

    /**
     * @Route("/contact-manager/{id}", name="contact_manager")
     */
    public function contactManager(User $manager, Request $request, MailManagerService $mailManager, TranslatorInterface $translator): Response
    {
        $this->denyAccessUnlessGranted('ROLE_TENANT');
        
        $message = new Message();
        $form = $this->createForm(MessageAnswerFormType::class, $message);
        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();

            $message->setSender($this->getUser());
            $em->persist($message);

            $msgRead = (new UserHasMessageRead())->setMessage($message)->setRecipient($manager);   
            $em->persist($msgRead);
            $em->flush();

            $subject = $translator->trans('A new message in your private area');
            $content = $translator->trans('Go to the message tab of your private area, you have received a new message.');

            $context = [
                'mail' => $this->getUser()->getEmail(),
                'firstname' => $this->getUser()->getFirstname(),
                'lastname' => $this->getUser()->getLastname(),
                'subject' => $subject,
                'message' => $content
            ];
            $mailManager->create(
                $this->getUser()->getEmail(),
                $manager->getEmail(),
                $subject,
                'private_area/message/email',
                $context
            );

            $this->addFlash('message_alert', [
                'text' => $translator->trans('your message has been sent successfully'), 
                'style' => 'success'
            ]);

            return $this->redirectToRoute('private_area_message_list');
        }

        return $this->render('private_area/message/contact_manager.html.twig', [
            'navigationPrivate' => true,
            'active' => 'message',
            'form' => $form->createView(),
            'manager' => $manager
        ]);
    }

    /**
     * @Route("/sent/{sortBy}/{order}", name="sent", defaults={"sortBy": "is_read", "order": "asc"})
     */
    public function sent($sortBy, $order, MessageRepository $msgRepo, ConfigMessageTableService $configMessageTableService, Request $request, PaginatorInterface $paginator): Response
    {
        $datas = $configMessageTableService->configInitMessageSentTable($msgRepo, $sortBy, $order, $this->getUser()->getId());

        $messages = $paginator->paginate(
            $datas['table'],
            $request->query->getInt('page', 1),
            10);

        return $this->render('private_area/message/sent.html.twig', [
            'navigationPrivate' => true,
            'active' => $datas['activeTab'],
            'headers' => $datas['headers'],
            'datas' => $messages,
        ]);
    }
}
