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

    /**
     * @param array<string> $participants
     */
    public function __construct(
        #[ORM\Column(type: 'integer')]
        private int $amount,
        #[ORM\Column(type: 'string', length: 60)]
        private string $payer,
        #[ORM\Column(type: 'simple_array')]
        private array $participants,
        #[ORM\Column(type: 'string')]
        private string $description
    ) {
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getAmount(): int
    {
        return $this->amount;
    }

    public function getPayer(): string
    {
        return $this->payer;
    }

    /**
     * @return array<string>
     */
    public function getParticipants(): array
    {
        return $this->participants;
    }

    public function getAmountByParticipant(): float
    {
        return $this->amount / count($this->participants);
    }

    public function getDescription(): string
    {
        return $this->description;
    }
}
