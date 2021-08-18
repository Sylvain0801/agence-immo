<?php

namespace App\Service;

use App\Repository\Property\OfferRepository;
use App\Repository\Property\PropertyRepository;
use Symfony\Contracts\Translation\TranslatorInterface;

class ConfigOfferTableService
{
  private $translator;

  private $propertyRepo;

  public function __construct(TranslatorInterface $translator, PropertyRepository $propertyRepo)
  {
      $this->translator = $translator;
	  $this->propertyRepo = $propertyRepo;
  }

  public function configInitOfferTable(OfferRepository $offerRepo): array
  {
    $datas = [
      'table' => $offerRepo->findArrayAllDatas(),
      'activeTab' => 'offer',
      'headers' => $this->configHeadersOfferTable($offerRepo)
      ]; 
    
    return $datas;
  }

  public function configSortedFilteredOfferTable(OfferRepository $offerRepo, $criterias, $sort, $order): array
  {
    $datas = [
      'table' => $offerRepo->findListSortedFilteredBycriteria($criterias, $sort, $order),
      'activeTab' => 'offer',
      'headers' => $this->configHeadersOfferTable($offerRepo)
      ]; 
    
    return $datas;
  }

  private function configHeadersOfferTable(OfferRepository $offerRepo): array
  {

    $headers = [
		'id' => [
			'header' =>true,
			'label' => $this->translator->trans('property add no'), 
			'sort' => true, 
			'filter' => true, 
			'type' => 'checkbox',
			'values' => $offerRepo->findListByFieldSortAscending('id')
		],
		'property_id' => [
			'header' =>true,
			'label' => $this->translator->trans('property no'), 
			'sort' => true, 
			'filter' => true, 
			'type' => 'checkbox',
			'values' => $offerRepo->findListByPropertyIdSortAscending()
		],
      	'title' => [
			'header' =>true,
			'label' => $this->translator->trans('title'), 
			'sort' => true, 
			'filter' => true, 
			'type' => 'checkbox',
			'values' => $offerRepo->findListByFieldSortAscending('title')
		],
		'description' => [
			'header' =>true,
			'label' => $this->translator->trans('description'), 
			'sort' => false, 
			'filter' => false,
			'type' => 'checkbox',
			'values' => $offerRepo->findListByFieldSortAscending('description')
      	],
      	'is_active' => [
			'header' =>true,
			'label' => $this->translator->trans('active'), 
			'sort' => true, 
			'filter' => false,
			'type' => 'checkbox',
			'values' => $offerRepo->findListByFieldSortAscending('is_active')
      	],
      	'images' => [
			'header' =>true,
			'label' => $this->translator->trans('images'), 
			'sort' => false, 
			'filter' => false,
      	],
		'transaction_type' => [
			'header' => false,
			'label' => $this->translator->trans('transaction'), 
			'sort' => false, 
			'filter' => true, 
			'type' => 'checkbox',
			'values' => $this->propertyRepo->findListByFieldSortAscending('transaction_type')
		],
		'city' => [
			'header' => false,
			'label' => $this->translator->trans('city'), 
			'sort' => false, 
			'filter' => true,
			'type' => 'checkbox',
			'values' => $this->propertyRepo->findListCitiesByCriteria()
		],
		'property_type' => [
			'header' => false,
			'label' => $this->translator->trans('type'), 
			'sort' => false, 
			'filter' => true,
			'type' => 'checkbox',
			'values' => $this->propertyRepo->findListTypesByCriteria()
		],
		'price' => [
			'header' => false,
			'label' => $this->translator->trans('price'), 
			'sort' => false, 
			'filter' => true, 
			'type' => 'number',
		],
		'area' => [
			'header' => false,
			'label' => $this->translator->trans('area'), 
			'sort' => false, 
			'filter' => true, 
			'type' => 'number',
		],
		'rooms' => [
			'header' => false,
			'label' => $this->translator->trans('rooms'), 
			'sort' => false, 
			'filter' => true, 
			'type' => 'number',
		],
		'energy' => [
			'header' => false,
			'label' => $this->translator->trans('energy'), 
			'sort' => false, 
			'filter' => true, 
			'type' => 'checkbox',
			'values' => $this->propertyRepo->findListByFieldSortAscending('energy')
		],
		'ges' => [
			'header' => false,
			'label' => $this->translator->trans('ghg'), 
			'sort' => false, 
			'filter' => true, 
			'type' => 'checkbox',
			'values' => $this->propertyRepo->findListByFieldSortAscending('ges')
		],
		'options' => [
			'header' => false,
			'label' => $this->translator->trans('options'), 
			'sort' => false, 
			'filter' => true,
			'type' => 'checkbox',
			'values' => $this->propertyRepo->findListOptions()
		],
		'owner' => [
			'header' => false,
			'label' => $this->translator->trans('owner'), 
			'sort' => false, 
			'filter' => true,
			'type' => 'checkbox',
			'values' => $this->propertyRepo->findListOwners()
		],
      	'actions' => [
			'header' =>true,
			'label' => $this->translator->trans('actions'), 
			'sort' => false, 
			'filter' => false
      ]
    ];
    return $headers;
  }
}