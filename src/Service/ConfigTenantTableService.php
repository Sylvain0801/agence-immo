<?php

namespace App\Service;

use App\Repository\User\TenantRepository;
use Symfony\Contracts\Translation\TranslatorInterface;

class ConfigTenantTableService
{
  private $translator;

  public function __construct(TranslatorInterface $translator)
  {
      $this->translator = $translator;
  }

  public function configInitDocumentTable(TenantRepository $tenantRepo, string $sortBy, string $order, int $userId): array
  {
    $datas = [
      'table' => $tenantRepo->findArrayAllDatas($sortBy, $order, $userId),
      'activeTab' => 'tenant',
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