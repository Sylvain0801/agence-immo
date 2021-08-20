<?php

namespace App\Service;

use App\Repository\DocumentRepository;
use Symfony\Contracts\Translation\TranslatorInterface;

class ConfigDocumentTableService
{
  private $translator;

  public function __construct(TranslatorInterface $translator)
  {
      $this->translator = $translator;
  }

  public function configInitDocumentTable(DocumentRepository $documentRepo): array
  {
    $datas = [
      'table' => $documentRepo->findArrayAllDatas(),
      'activeTab' => 'document',
      'headers' => $this->configHeadersDocumentTable($documentRepo)
      ]; 
    
    return $datas;
  }

  public function configSortedFilteredDocumentTable(DocumentRepository $documentRepo, $criterias, $sort, $order): array
  {
    $datas = [
      'table' => $documentRepo->findListSortedFilteredBycriteria($criterias, $sort, $order),
      'activeTab' => 'document',
      'headers' => $this->configHeadersDocumentTable($documentRepo)
      ]; 
    
    return $datas;
  }

  private function configHeadersDocumentTable(DocumentRepository $documentRepo): array
  {
    $headers = [
		'title' => [
			'header' =>true,
			'label' => $this->translator->trans('title'), 
			'sort' => true, 
			'filter' => false, 
			'type' => 'checkbox',
			// 'values' => $offerRepo->findListByPropertyIdSortAscending()
		],
		'users' => [
			'header' =>true,
			'label' => $this->translator->trans('users with access to the document'), 
			'sort' => false, 
			'filter' => false,
			'type' => 'checkbox',
			// 'values' => $offerRepo->findListByFieldSortAscending('description')
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