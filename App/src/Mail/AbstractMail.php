<?php

namespace App\Mail;

abstract class AbstractMail implements MailInterface
{
    public function __construct(
        private string $from,
        private string $to,
        private string $subject,
        private string $template
    ) {}

    public function getTemplate(): string
    {
        return $this->template;
    }

    public function getFrom(): string
    {
        return $this->from;
    }

    public function getTo(): string
    {
        return $this->to;
    }

    public function getSubject(): string
    {
        return $this->subject;
    }

    public function getContext(): array
    {
        return [];
    }
}
