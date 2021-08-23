<?php

namespace App\Service;

use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Mailer\MailerInterface;

class MailManagerService
{
  private $mailer;

  public function __construct(MailerInterface $mailer)
  {
      $this->mailer = $mailer;
  }
  
  public function create(
	string $from,
	string $to,
	string $subject,
	string $template,
	array $context
	)
  {
	$email = (new TemplatedEmail())
            ->from($from)
            ->to($to)
            ->subject($subject)
            ->htmlTemplate("$template.html.twig")
            ->context($context);
        $this->mailer->send($email);
  }
}