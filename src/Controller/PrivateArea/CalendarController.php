<?php

namespace App\Controller\PrivateArea;

use App\Entity\Calendar;
use App\Repository\CalendarRepository;
use App\Service\CalendarManagerService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Translation\TranslatorInterface;

/**
 * @Route("/private-area/calendar", name="private_area_calendar_")
 */
class CalendarController extends AbstractController
{
    private $translator;

    public function __construct(TranslatorInterface $translator)
    {
        $this->translator = $translator;
    }

    /**
     * @Route("/", name="list")
     */
    public function list(CalendarManagerService $calendarManager): Response
    {
        $data = $calendarManager->getAllDatas($this->getUser());

        return $this->render('private_area/calendar/list.html.twig', [
            'navigationPrivate' => true,
            'data' => $data,
            'active' => 'calendar'
        ]);
    }

    /**
     * @Route("/edit/{id}", name="edit", methods={"POST"})
     */
    public function edit(Request $request, Calendar $calendar, CalendarManagerService $calendarManager): Response
    {
        $tenantId = $calendar->getTenant()->getId();
        if ($request->isMethod('POST')) {
            $calendarManager->editRemind($calendar, $request, $calendar->getTenant());
            $this->addFlash('message_alert', [
                'text' => $this->translator->trans('The remind has been changed successfully'), 
                'style' => 'success'
            ]);
        } else {
            $this->addFlash('message_alert', [
                'text' => $this->translator->trans('An error occurred, please try again later'), 
                'style' => 'danger'
            ]);
        }
        
        
        return $this->redirectToRoute('private_area_tenant_calendar', ['id' => $tenantId], Response::HTTP_SEE_OTHER);
    }

    /**
     * @Route("/edit-all/{id}/{repeatId}", name="edit_all", methods={"POST"})
     */
    public function editAll(Calendar $calendar, $repeatId, Request $request, CalendarManagerService $calendarManager): Response
    {
        $tenantId = $calendar->getTenant()->getId();
        if ($request->isMethod('POST')) {
            $calendarManager->editRemindAll($repeatId, $request, $calendar->getTenant());
            $this->addFlash('message_alert', [
                'text' => $this->translator->trans('The remind has been changed successfully'), 
                'style' => 'success'
            ]);
        } else {
            $this->addFlash('message_alert', [
                'text' => $this->translator->trans('An error occurred, please try again later'), 
                'style' => 'danger'
            ]);
        }
        
        
        return $this->redirectToRoute('private_area_tenant_calendar', ['id' => $tenantId], Response::HTTP_SEE_OTHER);
    }

    /**
     * @Route("/delete/{id}", name="delete", methods={"POST"})
     */
    public function delete(Calendar $calendar, CalendarManagerService $calendarManager, Request $request): Response
    {
        $tenantId = $calendar->getTenant()->getId();
        $submittedToken = $request->request->get('delete_token');
        if ($this->isCsrfTokenValid('delete-reminder', $submittedToken)) {
            $calendarManager->deleteRemind($calendar);
            $this->addFlash('message_alert', [
                'text' => $this->translator->trans('The remind has been deleted successfully'), 
                'style' => 'success'
            ]);
        } else {
            $this->addFlash('message_alert', [
                'text' => $this->translator->trans('An error occurred, please try again later'), 
                'style' => 'danger'
            ]);
        }
        
        
        return $this->redirectToRoute('private_area_tenant_calendar', ['id' => $tenantId], Response::HTTP_SEE_OTHER);
    }

    /**
     * @Route("/delete-all/{id}/{repeatId}", name="delete_all", methods={"POST"})
     */
    public function deleteAll(Calendar $calendar, $repeatId, CalendarManagerService $calendarManager, Request $request): Response
    {
        $tenantId = $calendar->getTenant()->getId();
        $submittedToken = $request->request->get('delete_token');
        if ($this->isCsrfTokenValid('delete-reminder', $submittedToken)) {
            $calendarManager->deleteRemindAll($repeatId);
            $this->addFlash('message_alert', [
                'text' => $this->translator->trans('All reminders have been deleted successfully'), 
                'style' => 'success'
            ]);
        } else {
            $this->addFlash('message_alert', [
                'text' => $this->translator->trans('An error occurred, please try again later'), 
                'style' => 'danger'
            ]);
        }

        return $this->redirectToRoute('private_area_tenant_calendar', ['id' => $tenantId], Response::HTTP_SEE_OTHER);
    }
}
