<?php

namespace App\Service;

use App\Repository\User\OwnerRepository;
use Symfony\Contracts\Translation\TranslatorInterface;

class ConfigOwnerTableService
{
  private $translator;

  public function __construct(TranslatorInterface $translator)
  {
      $this->translator = $translator;
  }

  public function configInitDocumentTable(OwnerRepository $ownerRepo, string $sortBy, string $order, int $userId): array
  {
    $datas = [
      'table' => $ownerRepo->findArrayAllDatas($sortBy, $order, $userId),
      'activeTab' => 'owner',
      'headers' => $this->configHeadersDocumentTable()
      ]; 
    
    return $datas;
  }

  private function configHeadersDocumentTable(): array
  {
    $headers = [
		'lastname' => [
			'header' =>true,
			'label' => $this->translator->trans('lastname'), 
			'sort' => true, 
			'filter' => false
		],
		'firstname' => [
			'header' =>true,
			'label' => $this->translator->trans('firstname'), 
			'sort' => true, 
			'filter' => false
      	],
		'id' => [
			'header' =>true,
			'label' => $this->translator->trans('id'), 
			'sort' => true, 
			'filter' => false
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