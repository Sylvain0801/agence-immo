<?php

namespace App\Service;

use App\Entity\User\User;
use App\Repository\Document\DocHasSeenRepository;
use Symfony\Contracts\Translation\TranslatorInterface;

class ConfigDocumentTableService
{
	private $docHasSeenRepo;

  	private $translator;

	public function __construct(DocHasSeenRepository $docHasSeenRepo, TranslatorInterface $translator)
	{
		$this->docHasSeenRepo = $docHasSeenRepo;
		$this->translator = $translator;
	}

  public function configInitDocumentTable($sortBy, $order, ?User $user): array
  {
    $datas = [
      'table' => $this->docHasSeenRepo->findArrayAllDatas($sortBy, $order, $user),
      'activeTab' => 'document',
      'headers' => $this->configHeadersDocumentTable()
      ]; 
    
    return $datas;
  }

  private function configHeadersDocumentTable(): array
  {
    $headers = [
		'new' => [
			'header' =>true,
			'label' => $this->translator->trans('new'), 
			'sort' => true, 
			'filter' => false, 
		],
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
        'created_at' => [
          'header' =>true,
          'label' => $this->translator->trans('created on'), 
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