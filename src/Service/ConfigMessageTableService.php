<?php

namespace App\Service;

use App\Repository\Message\MessageRepository;
use App\Repository\Message\UserHasMessageReadRepository;
use Symfony\Contracts\Translation\TranslatorInterface;

class ConfigMessageTableService
{
  private $translator;

  public function __construct(TranslatorInterface $translator)
  {
      $this->translator = $translator;
  }

  public function configInitMessageTable(UserHasMessageReadRepository $msgReadRepo, string $sortBy, string $order, int $userId): array
  {
    $datas = [
      'table' => $msgReadRepo->findArrayAllDatas($sortBy, $order, $userId),
      'activeTab' => 'message',
      'headers' => $this->configHeadersMessageTable()
      ]; 
    
    return $datas;
  }

  public function configInitMessageSentTable(MessageRepository $msgRepo, string $sortBy, string $order, int $userId): array
  {
    $datas = [
      'table' => $msgRepo->findArrayMessageSent($sortBy, $order, $userId),
      'activeTab' => 'message',
      'headers' => $this->configHeadersMessageSentTable()
      ]; 
    
    return $datas;
  }

  private function configHeadersMessageSentTable(): array
  {
    $headers = [
		'recipient' => [
			'header' =>true,
			'label' => $this->translator->trans('recipients'), 
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

  private function configHeadersMessageTable(): array
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