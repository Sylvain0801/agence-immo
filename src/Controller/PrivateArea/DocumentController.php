<?php

namespace App\Controller\PrivateArea;

use App\Entity\Document\DocHasSeen;
use App\Entity\Document\Document;
use App\Entity\Document\UserHasDocSeen;
use App\Entity\Message\Message;
use App\Entity\Message\UserHasMessageRead;
use App\Form\PrivateArea\DocumentAddEditFormType;
use App\Form\PrivateArea\DocumentManageUsersAccessFormType;
use App\Repository\Document\DocHasSeenRepository;
use App\Repository\Document\DocumentRepository;
use App\Service\ConfigDocumentTableService;
use App\Service\MailManagerService;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Translation\TranslatorInterface;

/**
 * @Route("/private-area/document", name="private_area_document_")
 */
class DocumentController extends AbstractController
{  
    private $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }
    /**
     * @Route("/list/{sortBy}/{order}", name="list", defaults={"sortBy": "title", "order": "asc"})
     */
    public function list($sortBy, $order, ConfigDocumentTableService $configDocumentTableService, Request $request, TranslatorInterface $translator, PaginatorInterface $paginator): Response
    {
        $datas = $configDocumentTableService->configInitDocumentTable($sortBy, $order, $this->getUser());
        $document = new Document();
        $form = $this->createForm(DocumentAddEditFormType::class, $document);
        $formData = $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid()) {

            $doc = $formData->get('document')->getData();
            $newFileName = md5(uniqid()).'.'.$doc->guessExtension();
            $doc->move(dirname(__FILE__, 4).str_replace('/', DIRECTORY_SEPARATOR, '/public/uploads/documents/'), $newFileName);

            $document->setPath('uploads/documents/'.$newFileName);

            $this->em->persist($document);
            $this->em->flush();

            $this->addFlash('message_alert', [
                'text' => $translator->trans('The document has been created successfully'), 
                'style' => 'success'
            ]);

            return $this->redirectToRoute('private_area_document_list');
        }

        $documents = $paginator->paginate(
            $datas['table'],
            $request->query->getInt('page', 1),
            8);

        return $this->render('private_area/document/list.html.twig', [
            'navigationPrivate' => true,
            'active' => $datas['activeTab'],
            'headers' => $datas['headers'],
            'datas' => $documents,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/manage-users-access/{id}", name="manage_users_access")
     */
    public function manageUsersAccess(Document $document, DocHasSeenRepository $docHasSeenRepo, Request $request, MailManagerService $mailManager, TranslatorInterface $translator): Response
    {
        $request->getSession()->set('referer', $request->headers->get('referer'));
        $form = $this->createForm(DocumentManageUsersAccessFormType::class, $document);
        $formData = $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            
            
            $users = $formData->get('users')->getData();
            foreach ($users as $user) {
                $hasDocument = false;
                $hasDocument = $docHasSeenRepo->findOneBy(['document' => $document, 'user' => $user->getUser()]);
                if ($user->getUser() !== $this->getUser() && !$hasDocument) {
                    $subject = $translator->trans('A new document in your private area');
                    $content = $translator->trans('Go to the document tab of your private area, you have received a new document.');
                    $message = (new Message())
                            ->setSubject($subject)
                            ->setContent($content)
                            ->setSender($this->getUser());
                    $this->em->persist($message);
    
                    $msgRead = (new UserHasMessageRead)
                            ->setMessage($message)
                            ->setRecipient($user->getUser());
                    $this->em->persist($msgRead);
    
                    $context = [
                        'mail' => $this->getUser()->getEmail(),
                        'firstname' => $this->getUser()->getFirstname(),
                        'lastname' => $this->getUser()->getLastname(),
                        'subject' => $subject,
                        'message' => $content
                    ];
                    $mailManager->create(
                        $this->getUser()->getEmail(),
                        $user->getUser()->getEmail(),
                        $subject,
                        'private_area/document/email',
                        $context
                    );
                }
            }
            
            $this->em->persist($document);
            
            if (!$document->getUsers()->contains($this->getUser())) {
                $docHasSeen = (new DocHasSeen())->setDocument($document)->setUser($this->getUser());
                $this->em->persist($docHasSeen);
            }
            
            $this->em->flush();

            $this->addFlash('message_alert', [
                'text' => $translator->trans('Users access has been changed successfully'), 
                'style' => 'success'
            ]);

            return $this->redirectToRoute('private_area_document_list');
        }

        return $this->render('private_area/document/manage_users_access.html.twig', [
            'navigationPrivate' => true,
            'active' => 'document',
            'form' => $form->createView(),
            'documentId' => $document->getId()
        ]);
    }

    /**
     * @Route("/seen/{id}", name="seen")
     */
    public function seen(Document $document, DocHasSeenRepository $docHasSeenRepo): Response
    {
        $docHasSeen = $docHasSeenRepo->findOneBy(['document' => $document, 'user' => $this->getUser()]);

        $docHasSeen->setHasSeen($docHasSeen->getHasSeen() === true ? false : true);
        
        $this->em->persist($docHasSeen);
        $this->em->flush(); 

        return new Response('true');

    }

    /**
     * @Route("/delete/{id}", name="delete")
     */
    public function delete(DocHasSeen $docHasSeen, Request $request, TranslatorInterface $translator): Response
    {
        $submittedToken = $request->request->get('delete_document_token');
        if ($this->isCsrfTokenValid('delete-document-' . $docHasSeen->getId(), $submittedToken)) {
            $this->em->remove($docHasSeen);
            $this->em->flush();

            $this->addFlash('message_alert', [
                'text' => $translator->trans('The document has been deleted successfully'), 
                'style' => 'success'
            ]);
        } else {
            $this->addFlash('message_alert', [
                'text' => $translator->trans('An error occurred, please try again later'), 
                'style' => 'danger'
            ]);
        }

        return $this->redirectToRoute('private_area_document_list');

    }
}
