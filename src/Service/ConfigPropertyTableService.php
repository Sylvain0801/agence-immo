<?php

namespace App\Service;

use App\Entity\User\User;
use App\Repository\Property\PropertyRepository;
use Symfony\Contracts\Translation\TranslatorInterface;

class ConfigPropertyTableService
{
  private $translator;

  public function __construct(TranslatorInterface $translator)
  {
      $this->translator = $translator;
  }

  public function configInitPropertyTable(PropertyRepository $propertyRepo, ?User $user): array
  {
    $datas = [
      'table' => $propertyRepo->findArrayAllDatas($user),
      'activeTab' => 'property',
      'headers' => $this->configHeadersPropertyTable($propertyRepo, $user)
      ]; 
    
    return $datas;
  }

  public function configSortedFilteredPropertyTable(PropertyRepository $propertyRepo, $criterias, $sortBy, $order, ?User $user): array
  {
    $datas = [
      'table' => $propertyRepo->findListSortedFilteredBycriteria($criterias, $sortBy, $order, $user),
      'activeTab' => 'property',
      'headers' => $this->configHeadersPropertyTable($propertyRepo, $user)
      ]; 
    
    return $datas;
  }

  private function configHeadersPropertyTable(PropertyRepository $propertyRepo, ?User $user): array
  {

    $headers = [
		'id' => [
			'header' => true,
			'label' => $this->translator->trans('id'), 
			'sort' => true, 
			'filter' => true, 
			'type' => 'checkbox',
			'values' => $propertyRepo->findListByFieldSortAscending('id')
		],
		'transaction_type' => [
			'header' => true,
			'label' => $this->translator->trans('transaction'), 
			'sort' => true, 
			'filter' => true, 
			'type' => 'checkbox',
			'values' => $propertyRepo->findListByFieldSortAscending('transaction_type')
		],
		'property_tenants' => [
			'header' => true,
			'label' => $this->translator->trans('status'), 
			'sort' => false, 
			'filter' => false,
		],
		'city' => [
			'header' => true,
			'label' => $this->translator->trans('city'), 
			'sort' => true, 
			'filter' => true,
			'type' => 'checkbox',
			'values' => $propertyRepo->findListCitiesByCriteria()
		],
		'property_type' => [
			'header' => true,
			'label' => $this->translator->trans('type'), 
			'sort' => true, 
			'filter' => true,
			'type' => 'checkbox',
			'values' => $propertyRepo->findListTypesByCriteria()
		],
		'price' => [
			'header' => true,
			'label' => $this->translator->trans('price'), 
			'sort' => true, 
			'filter' => true, 
			'type' => 'number',
		],
		'area' => [
			'header' => true,
			'label' => $this->translator->trans('area'), 
			'sort' => true, 
			'filter' => true, 
			'type' => 'number',
		],
		'rooms' => [
			'header' => true,
			'label' => $this->translator->trans('rooms'), 
			'sort' => true, 
			'filter' => true, 
			'type' => 'number',
		],
		'energy' => [
			'header' => true,
			'label' => $this->translator->trans('energy'), 
			'sort' => true, 
			'filter' => true, 
			'type' => 'checkbox',
			'values' => $propertyRepo->findListByFieldSortAscending('energy')
		],
		'ges' => [
			'header' => true,
			'label' => $this->translator->trans('ghg'), 
			'sort' => true, 
			'filter' => true, 
			'type' => 'checkbox',
			'values' => $propertyRepo->findListByFieldSortAscending('ges')
		],
		'options' => [
			'header' => true,
			'label' => $this->translator->trans('options'), 
			'sort' => false, 
			'filter' => true,
			'type' => 'checkbox',
			'values' => $propertyRepo->findListOptions()
		],
		'owner_property' => [
			'header' => in_array('AGENT', $user->getRoles()) ? true : false,
			'label' => $this->translator->trans('owner'), 
			'sort' => true, 
			'filter' => true,
			'type' => 'checkbox',
			'values' => $propertyRepo->findListOwners()
		],
		'manager_property' => [
			'header' => in_array('OWNER', $user->getRoles()) ? true : false,
			'label' => $this->translator->trans('manager'), 
			'sort' => true, 
			'filter' => false,
		],
		'offer' => [
			'header' => true,
			'label' => $this->translator->trans('property ad'), 
			'sort' => true, 
			'filter' => false
		],
		'actions' => [
			'header' => true,
			'label' => $this->translator->trans('actions'), 
			'sort' => false, 
			'filter' => false
		]
    ];

    return $headers;
  }
}