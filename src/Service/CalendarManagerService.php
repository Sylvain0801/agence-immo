<?php

namespace App\Service;

use App\Entity\Calendar;
use App\Entity\User\Tenant;
use App\Form\PrivateArea\CalendarType;
use App\Repository\CalendarRepository;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

class CalendarManagerService
{
	private $em;

	private $calendarRepo;

	private $translator;

	private $router;

	private $formFactory;

	public function __construct(
		EntityManagerInterface $em, 
		CalendarRepository $calendarRepo, 
		TranslatorInterface $translator, 
		UrlGeneratorInterface $router,
		FormFactoryInterface $formFactory
		)
	{
		$this->em = $em;
		$this->calendarRepo = $calendarRepo;
		$this->translator = $translator;
		$this->router = $router;
		$this->formFactory = $formFactory;
	}

	public function getCalendarByIdOrCreateNew(?int $repeatId): ?Calendar
	{
		$repeatId ? $calendar =  $this->calendarRepo->findOneBy(['id' => $repeatId]) : $calendar = new Calendar;
		
		return $calendar;
	}

	public function getAllDatas(Tenant $tenant): string
	{
		$calendarEvents = $this->calendarRepo->findBy(['tenant' => $tenant->getId()]);
		$data = [];
		foreach ($calendarEvents as $event) {
			$id = $event->getId();
			$repeatId = $event->getRepeatId();
			$data[] = [
				'id' => $event->getId(),
				'start' => $event->getStart()->format('Y-m-d H:i:s'),
				'repeatEnd' => $event->getRepeatEnd(),
				'title' => $event->getTitle(),
				'frequency' => $event->getFrequency(),
				'isRepeated' => $event->getIsRepeated(),
				'description' => $event->getDescription(),
				'backgroundColor' => $event->getColor(),
				'borderColor' => $event->getColor(),
				'pathEditAll' => $repeatId ? $this->router->generate('private_area_calendar_edit_all', ['id' => $id, 'repeatId' => $repeatId]) : null,
				'pathEdit' => $this->router->generate('private_area_calendar_edit', ['id' => $id]),
				'pathDelete' => $this->router->generate('private_area_calendar_delete', ['id' => $id]),
				'pathDeleteAll' => $repeatId ? $this->router->generate('private_area_calendar_delete_all', ['id' => $id, 'repeatId' => $repeatId]) : null
			];
		}

		return json_encode($data);
	}

	public function createRemind(Calendar $calendar, object $formDatas, Tenant $tenant): void
	{
		$dateStart = $this->convertStringToDateTime($formDatas->get('reminder_date')->getData());
		$dateEnd = $this->convertStringToDateTime($formDatas->get('repeat_end')->getData());
		$calendar->setStart($dateStart)->setRepeatEnd($dateEnd)->setIsRepeated($formDatas->get('is_repeated')->getData());
		$description = $formDatas->get('description')->getData();
		empty($description) ? $calendar->setDescription($this->translator->trans('no description')) : $calendar->setDescription($description);
		$calendar->setColor($formDatas->get('color')->getData())
			->setTenant($tenant)
			->setTitle($formDatas->get('title')->getData())
			->setFrequency($formDatas->get('frequency')->getData());
		$this->em->persist($calendar);
		$this->em->flush();
	}

	public function createRemindRepeat(Calendar $calendar, object $formDatas, Tenant $tenant): void
	{
		$repeatId = $calendar->getId();
		$calendar->setRepeatId($repeatId);
		$this->convertStringToDateTimeRepeat($formDatas, $repeatId, $tenant);
		$this->em->flush();
	}
	
	public function editRemind(Calendar $calendar, Request $request, Tenant $tenant): void
	{
		$form = $this->formFactory->create(CalendarType::class);
		$formDatas = $form->handleRequest($request);

		$this->createRemind($calendar, $formDatas, $tenant);
		
	}
	
	public function editRemindAll(int $repeatId, Request $request, Tenant $tenant): void
	{
		$form = $this->formFactory->create(CalendarType::class);
		$formDatas = $form->handleRequest($request);
		$this->deleteRemindAll($repeatId);
		$calendar = new Calendar();

		$this->createRemind($calendar, $formDatas, $tenant);
			
		if ($formDatas->get('is_repeated')->getData()) {
			$dateEnd = $this->convertStringToDateTime($formDatas->get('repeat_end')->getData());
			$calendar->setRepeatEnd($dateEnd)->setIsRepeated($formDatas->get('is_repeated')->getData());
			$calendar->setFrequency($formDatas->get('frequency')->getData());

			$this->createRemindRepeat($calendar, $formDatas, $tenant);
		}
	}

	public function deleteRemind(Calendar $calendar): void
	{
		$this->em->remove($calendar);
		$this->em->flush();
	}
	
	public function deleteRemindAll(int $repeatId): void
	{
		$reminds = $this->calendarRepo->findBy(['repeat_id' => $repeatId]);
		foreach ($reminds as $remind) {
			$this->em->remove($remind);
		}
		$this->em->flush();
	}

	private function convertStringToDateTime(?string $dateString): ?DateTime
	{
		if ($dateString !== null) {
			$newDate = implode('-', array_reverse(explode('/', $dateString)));
			return new DateTime($newDate);
		} else {
			return null;
		}
	}
	
    private function convertStringToDateTimeRepeat(object $formDatas, int $repeatId, Tenant $tenant): void
    {
        $formatDateStringInit = implode('-', array_reverse(explode('/', $formDatas->get('reminder_date')->getData())));
        $formatDateStringEnd = implode('-', array_reverse(explode('/', $formDatas->get('repeat_end')->getData())));
        $dateEnd = new DateTime($formatDateStringEnd);
        $frequency = $formDatas->get('frequency')->getData();
        $description = $formDatas->get('description')->getData();
        if (empty($description)) $description = $this->translator->trans('no description');
        $k = 1;
        $dateRepeat = (new DateTime)->setTimestamp(strtotime("$formatDateStringInit +$k $frequency"));
        if ($dateRepeat < $dateEnd) {
            while ($dateRepeat <= $dateEnd) {
                $calendar = (new Calendar())
                    ->setStart($dateRepeat)->setRepeatEnd($dateEnd)->setIsRepeated(true)
                    ->setFrequency($formDatas->get('frequency')->getData())
                    ->setRepeatId($repeatId)
					->setTenant($tenant)
                    ->setTitle($formDatas->get('title')->getData())
                    ->setDescription($description)
                    ->setColor($formDatas->get('color')->getData());
                $this->em->persist($calendar);
                $k++;
                $dateRepeat = (new DateTime)->setTimestamp(strtotime("$formatDateStringInit +$k $frequency"));
            }
        }
    }
}
