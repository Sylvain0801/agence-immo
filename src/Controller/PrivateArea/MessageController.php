<?php

namespace App\Controller\PrivateArea;

use App\Entity\Message\Message;
use App\Entity\Message\UserHasMessageRead;
use App\Form\PrivateArea\MessageAnswerFormType;
use App\Repository\Message\UserHasMessageReadRepository;
use App\Service\ConfigMessageTableService;
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
        $datas = $configMessageTableService->configInitDocumentTable($msgReadRepo, $sortBy, $order, $this->getUser()->getId());

        $messages = $paginator->paginate(
            $datas['table'],
            $request->query->getInt('page', 1),
            12);

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

        if ($form->isSubmitted() && $form-> isValid()) {


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
}
