<?php

namespace App\Mail;

interface MailInterface
{
    public function getTemplate(): string;

    public function getFrom(): string;

    public function getTo(): string;

    public function getSubject(): string;

    public function getContext(): array;
}
