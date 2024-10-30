<?php

namespace App\Service;

use App\Mail\MailInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Mailer\MailerInterface;

class SendEmailService
{
    public function __construct(
        private MailerInterface $mailer
    ) {}

    public function send(
        MailInterface $mail
    ): void {

        $email = (new TemplatedEmail())
            ->from($mail->getFrom())
            ->to($mail->getTo())
            ->subject($mail->getSubject())
            ->htmlTemplate("emails/{$mail->getTemplate()}.html.twig")
            ->context($mail->getContext());

        $this->mailer->send($email);
    }
}
