<?php

namespace App\Controller\PrivateArea;

use App\Entity\Property\Property;
use App\Form\PrivateArea\PropertyAddEditFormType;
use App\Repository\Property\PropertyRepository;
use App\Service\CityManageService;
use App\Service\TableConfigService;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Translation\TranslatorInterface;

/**
 * @Route("/private-area/property", name="private_area_property_")
 */
class PropertyController extends AbstractController
{  
    /**
     * @Route("/list", name="list")
     */
    public function list(PropertyRepository $propertyRepo, TableConfigService $tableConfigService, Request $request, PaginatorInterface $paginator): Response
    {
        $request->getSession()->remove('filter_criterias');

        $datas = $tableConfigService->configInitTableProperty($propertyRepo);
        
        $properties = $paginator->paginate(
            $datas['table'],
            $request->query->getInt('page', 1),
            12);

        return $this->render('private_area/property/list.html.twig', [
            'navigationPrivate' => true,
            'active' => $datas['activeTab'],
            'headers' => $datas['headers'],
            'datas' => $properties
        ]);
    }

    /**
     * @Route("/list-sorted/{sort}/{order}", name="list_sorted", defaults={"sort": "id", "order": "asc"})
     */
    public function listSortedFilteredProperty($sort, $order, TableConfigService $tableConfigService, PropertyRepository $propertyRepo, Request $request, PaginatorInterface $paginator): Response
    {
        if (count($request->request) > 0) {
            $criterias = $request->request;
            $request->getSession()->set('filter_criterias', $criterias);
        } else if ($request->getSession()->get('filter_criterias')) {
            $criterias = $request->getSession()->get('filter_criterias');
        } else {
            $criterias = null;
        }
        dump($criterias);
        $datas = $tableConfigService->configSortedFilteredTableProperty($propertyRepo, $criterias, $sort, $order);

        $properties = $paginator->paginate(
            $datas['table'],
            $request->query->getInt('page', 1),
            12);

        return $this->render('private_area/property/list.html.twig', [
            'navigationPrivate' => true,
            'active' => $datas['activeTab'],
            'headers' => $datas['headers'],
            'datas' => $properties
        ]);
    }

     /**
     * @Route("/new", name="new")
     */
    public function new(Request $request, CityManageService $cityManageService, TranslatorInterface $translator): Response
    {
        $property = new Property();
        $form = $this->createForm(PropertyAddEditFormType::class, $property);
        $formData = $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form-> isValid()) {
            $em = $this->getDoctrine()->getManager();

            $zipCode = substr($formData->get('city')->getData(), 0, 5);
            $cityManageService->createCityIfNotExists($zipCode, $property);

            $property->setManager($this->getUser());
            $em->persist($property);
            $em->flush();

            $this->addFlash('message_alert', [
                'text' => $translator->trans('The property has been created successfully'), 
                'style' => 'success'
            ]);

            return $this->redirectToRoute('private_area_property_list');
        }

        return $this->render('private_area/property/new.html.twig', [
            'navigationPrivate' => true,
            'active' => 'property',
            'form' => $form->createView()
        ]);
    }

     /**
     * @Route("/edit/{id}", name="edit")
     */
    public function edit(Property $property, Request $request, CityManageService $cityManageService, TranslatorInterface $translator): Response
    {
        dump($request->request);
        $form = $this->createForm(PropertyAddEditFormType::class, $property);
        $formData = $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form-> isValid()) {
            $em = $this->getDoctrine()->getManager();

            $zipCode = substr($formData->get('city')->getData(), 0, 5);
            $cityManageService->createCityIfNotExists($zipCode, $property);

            $property->setManager($this->getUser());
            $em->persist($property);
            $em->flush();

            $this->addFlash('message_alert', [
                'text' => $translator->trans('The property has been modified successfully'), 
                'style' => 'success'
            ]);

            return $this->redirectToRoute('private_area_property_list');
        }

        return $this->render('private_area/property/edit.html.twig', [
            'navigationPrivate' => true,
            'active' => 'property',
            'form' => $form->createView(),
            'property' => $property
        ]);
    }
}
