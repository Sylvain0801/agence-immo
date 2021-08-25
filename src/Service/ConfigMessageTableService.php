<?php

namespace App\Service;

use App\Repository\Message\UserHasMessageReadRepository;
use Symfony\Contracts\Translation\TranslatorInterface;

class ConfigMessageTableService
{
  private $translator;

  public function __construct(TranslatorInterface $translator)
  {
      $this->translator = $translator;
  }

  public function configInitDocumentTable(UserHasMessageReadRepository $msgReadRepo, string $sortBy, string $order, int $userId): array
  {
    $datas = [
      'table' => $msgReadRepo->findArrayAllDatas($sortBy, $order, $userId),
      'activeTab' => 'message',
      'headers' => $this->configHeadersDocumentTable()
      ]; 
    
    return $datas;
  }

  private function configHeadersDocumentTable(): array
  {
    $headers = [
		'is_read' => [
			'header' =>true,
			'label' => $this->translator->trans('read'), 
			'sort' => true, 
			'filter' => false
		],
		'sender' => [
			'header' =>true,
			'label' => $this->translator->trans('sender'), 
			'sort' => true, 
			'filter' => false
		],
		'content' => [
			'header' =>true,
			'label' => $this->translator->trans('message'), 
			'sort' => true, 
			'filter' => false
      	],
		'created_at' => [
			'header' =>true,
			'label' => $this->translator->trans('sent at'), 
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