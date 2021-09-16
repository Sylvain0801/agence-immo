<?php

namespace App\Controller\PrivateArea;

use App\Entity\Property\Offer;
use App\Form\PrivateArea\OfferAddEditFormType;
use App\Repository\Property\ImageRepository;
use App\Repository\Property\OfferRepository;
use App\Repository\Property\PropertyRepository;
use App\Service\ConfigOfferTableService;
use App\Service\ImageManageService;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Translation\TranslatorInterface;

/**
 * @Route("/private-area/offer", name="private_area_offer_")
 */
class OfferController extends AbstractController
{  
    /**
     * @Route("/list", name="list")
     */
    public function list(OfferRepository $offerRepo, ConfigOfferTableService $configOfferTableService, Request $request, PaginatorInterface $paginator): Response
    {
        $request->getSession()->remove('filter_criterias');

        if ($request->getSession()->get('viewMode')) {
            $viewMode = $request->getSession()->get('viewMode');
        } else {
            $viewMode = 'list';
        }

        $datas = $configOfferTableService->configInitOfferTable($offerRepo);
        
        $offers = $paginator->paginate(
            $datas['table'],
            $request->query->getInt('page', 1),
            12);

        return $this->render('private_area/offer/list.html.twig', [
            'navigationPrivate' => true,
            'active' => $datas['activeTab'],
            'headers' => $datas['headers'],
            'datas' => $offers,
            'viewMode' => $viewMode
        ]);
    }

    /**
     * @Route("/list-sorted/{sortBy}/{order}/{viewMode}", name="list_sorted", defaults={"sortBy": "id", "order": "asc", "viewMode": null})
     */
    public function listSortedFilteredProperty($viewMode, $sortBy, $order, ConfigOfferTableService $configOfferTableService, OfferRepository $offerRepo, Request $request, PaginatorInterface $paginator): Response
    {
        if (count($request->request) > 0) {
            $criterias = $request->request;
            $request->getSession()->set('filter_criterias', $criterias);
        } else if ($request->getSession()->get('filter_criterias')) {
            $criterias = $request->getSession()->get('filter_criterias');
        } else {
            $criterias = null;
        }
        if ($viewMode === null && $request->getSession()->get('viewMode')) {
            $viewMode = $request->getSession()->get('viewMode');
        } else {
            $request->getSession()->set('viewMode', $viewMode);
        }

        $datas = $configOfferTableService->configSortedFilteredOfferTable($offerRepo, $criterias, $sortBy, $order);

        $offers = $paginator->paginate(
            $datas['table'],
            $request->query->getInt('page', 1),
            12);

        return $this->render('private_area/offer/list.html.twig', [
            'navigationPrivate' => true,
            'active' => $datas['activeTab'],
            'headers' => $datas['headers'],
            'datas' => $offers,
            'viewMode' => $viewMode
        ]);
    }

    /**
     * @Route("/view/{slug}", name="view")
     */
    public function view($slug, Offer $offer, Request $request): Response
    {
        $request->getSession()->set('referer', $request->headers->get('referer'));

        $offer = $this->getDoctrine()->getRepository(Offer::class)->findOneBy(['slug' => $slug]);

        $letters = ['A', 'B', 'C', 'D', 'E', 'F', 'G'];

        return $this->render('private_area/offer/view.html.twig', [
            'navigationPrivate' => true,
            'offer' => $offer,
            'active' => 'offer',
            'letters' => $letters
        ]);
    }

    /**
     * @Route("/active/{id}", name="active")
     */
    public function active(Offer $offer): Response
    {
        $em = $this->getDoctrine()->getManager();

        $offer->setIsActive($offer->getIsActive() ? false : true);

        $em->persist($offer);
        $em->flush();

        return new Response('true');
    }

    
    /**
     * @Route("/new/{propertyId}", name="new")
     */
    public function new($propertyId, PropertyRepository $propertyRepo, Request $request, TranslatorInterface $translator, ImageManageService $imageManageService): Response
    {
        $request->getSession()->set('referer', $request->headers->get('referer'));
        $property = $propertyRepo->findOneBy(['id' => $propertyId]);
        $offer = new Offer();
        $form = $this->createForm(OfferAddEditFormType::class, $offer);
        $formData = $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();

            $images = ($formData->get('images')->getData());
            $imageManageService->add($images, $offer);

            $property->setPropertyAdCount($property->getPropertyAdCount() + 1);
            $offer->setProperty($property);
            $offer->setIsActive(0);

            $em->persist($offer);
            $em->flush();

            $this->addFlash('message_alert', [
                'text' => $translator->trans('The property ad has been created successfully'), 
                'style' => 'success'
            ]);

            return $this->redirectToRoute('private_area_offer_list');
        }

        return $this->render('private_area/offer/new.html.twig', [
            'navigationPrivate' => true,
            'active' => 'offer',
            'form' => $form->createView(),
            'propertyId' => $propertyId
        ]);
    }

    /**
     * @Route("/edit/{slug}/{propertyId}", name="edit")
     */
    public function edit($slug, $propertyId, OfferRepository $offerRepo, Request $request, TranslatorInterface $translator, ImageManageService $imageManageService): Response
    {
        $request->getSession()->set('referer', $request->headers->get('referer'));
        $offer = $offerRepo->findOneBy(['slug' => $slug]);
        $form = $this->createForm(OfferAddEditFormType::class, $offer);
        $formData = $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();

            $images = ($formData->get('images')->getData());
            $imageManageService->add($images, $offer);

            $em->persist($offer);
            $em->flush();

            $this->addFlash('message_alert', [
                'text' => $translator->trans('The property ad has been modified successfully'), 
                'style' => 'success'
            ]);

            return $this->redirectToRoute('private_area_offer_list');
        }

        return $this->render('private_area/offer/edit.html.twig', [
            'navigationPrivate' => true,
            'active' => 'offer',
            'form' => $form->createView(),
            'propertyId' => $propertyId,
            'offer' => $offer
        ]);
    }

    /**
     * @Route("/delete/image/{offerId}/{imageId}", name="delete_image")
     */
    public function deleteImage($offerId, $imageId, OfferRepository $offerRepo, ImageRepository $imageRepo): Response
    {
        $offer = $offerRepo->findOneBy(['id' => $offerId]);
        $image = $imageRepo->findOneBy(['id' => $imageId]);
        
        $offer->removeImage($image);

        $em = $this->getDoctrine()->getManager();
        $em->persist($offer);
        $em->flush();

        

        return new Response('true');

    }

    /**
     * @Route("/delete/{id}", name="delete")
     */
    public function delete(Offer $offer, TranslatorInterface $translator): Response
    {
        $em = $this->getDoctrine()->getManager();
        $em->remove($offer);
        $em->flush();

        $this->addFlash('message_alert', [
            'text' => $translator->trans('The property ad has been deleted successfully'), 
            'style' => 'success'
        ]);

        return $this->redirectToRoute('private_area_offer_list');

    }
}
