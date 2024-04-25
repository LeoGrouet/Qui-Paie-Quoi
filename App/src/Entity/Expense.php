<?php

namespace App\Entity;

class Expense
{
    public int $amount;
    public string $payer;
    public array $participants;
    public string $description;

    public function __construct(int $amount, string $payer, array $participants, string $description)
    {
        $this->$amount;
        $this->$payer;
        $this->$participants;
        $this->$description;
    }
}
