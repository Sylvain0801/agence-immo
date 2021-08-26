<?php

namespace App\Service;

use App\Entity\User\User;
use App\Repository\DocumentRepository;
use Symfony\Contracts\Translation\TranslatorInterface;

class ConfigDocumentTableService
{
  private $translator;

  public function __construct(TranslatorInterface $translator)
  {
      $this->translator = $translator;
  }

  public function configInitDocumentTable(DocumentRepository $documentRepo, $order, ?User $user): array
  {
    $datas = [
      'table' => $documentRepo->findArrayAllDatas($order, $user),
      'activeTab' => 'document',
      'headers' => $this->configHeadersDocumentTable()
      ]; 
    
    return $datas;
  }

  private function configHeadersDocumentTable(): array
  {
    $headers = [
		'title' => [
			'header' =>true,
			'label' => $this->translator->trans('title'), 
			'sort' => true, 
			'filter' => false, 
		],
		'users' => [
			'header' =>true,
			'label' => $this->translator->trans('users with access to the document'), 
			'sort' => false, 
			'filter' => false,
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