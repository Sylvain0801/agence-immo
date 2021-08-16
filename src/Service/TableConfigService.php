<?php

namespace App\Service;

use App\Repository\Property\PropertyRepository;
use Symfony\Contracts\Translation\TranslatorInterface;

class TableConfigService
{
  private $translator;

  public function __construct(TranslatorInterface $translator)
  {
      $this->translator = $translator;
  }

  public function configInitTableProperty(PropertyRepository $propertyRepo): array
  {
    $datas = [
      'table' => $propertyRepo->findArrayAllDatas(),
      'activeTab' => 'property',
      'headers' => $this->configHeadersTableProperty($propertyRepo)
      ]; 
    
    return $datas;
  }

  public function configSortedFilteredTableProperty(PropertyRepository $propertyRepo, $criterias, $sort, $order): array
  {
    $datas = [
      'table' => $propertyRepo->findListSortedFilteredBycriteria($criterias, $sort, $order),
      'activeTab' => 'property',
      'headers' => $this->configHeadersTableProperty($propertyRepo)
      ]; 
    
    return $datas;
  }

  private function configHeadersTableProperty(PropertyRepository $propertyRepo): array
  {

    $headers = [
      'id' => [
          'label' => $this->translator->trans('id'), 
          'sort' => true, 
          'filter' => true, 
          'type' => 'checkbox',
          'values' => $propertyRepo->findListByFieldSortAscending('id')
      ],
      'transaction_type' => [
          'label' => $this->translator->trans('transaction'), 
          'sort' => true, 
          'filter' => true, 
          'type' => 'checkbox',
          'values' => $propertyRepo->findListByFieldSortAscending('transaction_type')
      ],
      'city' => [
          'label' => $this->translator->trans('city'), 
          'sort' => true, 
          'filter' => true,
          'type' => 'checkbox',
          'values' => $propertyRepo->findListCitiesByCriteria()
      ],
      'property_type' => [
          'label' => $this->translator->trans('type'), 
          'sort' => true, 
          'filter' => true,
          'type' => 'checkbox',
          'values' => $propertyRepo->findListTypesByCriteria()
      ],
      'price' => [
          'label' => $this->translator->trans('price'), 
          'sort' => true, 
          'filter' => true, 
          'type' => 'number',
      ],
      'area' => [
          'label' => $this->translator->trans('area'), 
          'sort' => true, 
          'filter' => true, 
          'type' => 'number',
      ],
      'rooms' => [
          'label' => $this->translator->trans('rooms'), 
          'sort' => true, 
          'filter' => true, 
          'type' => 'number',
      ],
      'energy' => [
          'label' => $this->translator->trans('energy'), 
          'sort' => true, 
          'filter' => true, 
          'type' => 'checkbox',
          'values' => $propertyRepo->findListByFieldSortAscending('energy')
      ],
      'ges' => [
          'label' => $this->translator->trans('ghg'), 
          'sort' => true, 
          'filter' => true, 
          'type' => 'checkbox',
          'values' => $propertyRepo->findListByFieldSortAscending('ges')
      ],
      'options' => [
          'label' => $this->translator->trans('options'), 
          'sort' => false, 
          'filter' => true,
          'type' => 'checkbox',
          'values' => $propertyRepo->findListOptions()
      ],
      'owner' => [
          'label' => $this->translator->trans('owner'), 
          'sort' => true, 
          'filter' => true,
          'type' => 'checkbox',
          'values' => $propertyRepo->findListOwners()
      ],
      'offer' => [
          'label' => $this->translator->trans('property ad'), 
          'sort' => true, 
          'filter' => false
      ],
      'actions' => [
          'label' => $this->translator->trans('actions'), 
          'sort' => false, 
          'filter' => false
      ]
    ];

    return $headers;
  }
}