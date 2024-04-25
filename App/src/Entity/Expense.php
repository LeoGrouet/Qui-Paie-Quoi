<?php

namespace App\Entity;

use App\Repository\ExpenseRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ExpenseRepository::class)]
class Expense
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private int $id;

    #[ORM\Column(type: 'integer')]
    private int $amount;

    #[ORM\Column(type: 'string', length: 60)]
    private string $payer;

    #[ORM\Column(type: 'array')]
    private array $participants;

    #[ORM\Column(type: 'string')]
    private string $description;

    public function __construct(int $amount, string $payer, array $participants, string $description)
    {
        $this->amount = $amount;
        $this->payer = $payer;
        $this->participants = $participants;
        $this->description = $description;
    }
}
