<?php

namespace App\Mail;

use App\Entity\User;

class ResetMail extends AbstractMail implements MailInterface
{
    public function __construct(
        string $from,
        private readonly User $recipient,
        private readonly string $token
    ) {
        parent::__construct($from, $recipient->getEmail(), 'reset_password', 'resetPassword');
    }

    public function getContext(): array
    {
        return [
            'username' => $this->recipient->getEmail(),
            'token' => $this->token
        ];
    }
}
