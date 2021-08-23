<?php

namespace App\Controller\PrivateArea;

use App\Entity\Document;
use App\Form\PrivateArea\DocumentAddEditFormType;
use App\Form\PrivateArea\DocumentManageUsersAccessFormType;
use App\Repository\DocumentRepository;
use App\Service\ConfigDocumentTableService;
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
    /**
     * @Route("/list/{order}", name="list", defaults={"order": "asc"})
     */
    public function list($order, DocumentRepository $documentRepo, ConfigDocumentTableService $configDocumentTableService, Request $request, TranslatorInterface $translator, PaginatorInterface $paginator): Response
    {
        $datas = $configDocumentTableService->configInitDocumentTable($documentRepo, $order);
        $document = new Document();
        $form = $this->createForm(DocumentAddEditFormType::class, $document);
        $formData = $form->handleRequest($request);

        if ($form->isSubmitted() && $form-> isValid()) {

            $doc = $formData->get('document')->getData();
            $newFileName = md5(uniqid()).'.'.$doc->guessExtension();
            $doc->move(dirname(__FILE__, 3).str_replace('/', DIRECTORY_SEPARATOR, '/public/uploads/documents/'), $newFileName);

            $document->setPath('uploads/documents/'.$newFileName);
            $document->addUser($this->getUser());

            $em = $this->getDoctrine()->getManager();
            $em->persist($document);
            $em->flush();

            $this->addFlash('message_alert', [
                'text' => $translator->trans('The document has been created successfully'), 
                'style' => 'success'
            ]);

            return $this->redirectToRoute('private_area_document_list');
        }

        $documents = $paginator->paginate(
            $datas['table'],
            $request->query->getInt('page', 1),
            12);

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
    public function manageUsersAccess(Document $document, Request $request, TranslatorInterface $translator): Response
    {
        $request->getSession()->set('referer', $request->headers->get('referer'));
        $form = $this->createForm(DocumentManageUsersAccessFormType::class, $document);
        $formData = $form->handleRequest($request);

        if ($form->isSubmitted() && $form-> isValid()) {

            $em = $this->getDoctrine()->getManager();
            $em->persist($document);
            $em->flush();

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
     * @Route("/delete/{id}", name="delete")
     */
    public function delete(Document $document, TranslatorInterface $translator): Response
    {
        $em = $this->getDoctrine()->getManager();
        $em->remove($document);
        $em->flush();

        $this->addFlash('message_alert', [
            'text' => $translator->trans('The document has been deleted successfully'), 
            'style' => 'success'
        ]);

        return $this->redirectToRoute('private_area_document_list');

    }
}
