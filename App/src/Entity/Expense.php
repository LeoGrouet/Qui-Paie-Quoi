<?php

namespace App\Entity;

use App\Repository\ExpenseRepository;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\JoinColumn;
use Doctrine\ORM\Mapping\ManyToMany;
use Doctrine\ORM\Mapping\ManyToOne;
use Doctrine\ORM\Mapping\OneToMany;
use Doctrine\ORM\Mapping\OneToOne;

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

        #[ORM\Column(type: 'string')]
        private string $description,

        #[OneToOne(targetEntity: User::class)]
        #[JoinColumn(name: 'user_id', referencedColumnName: 'id')]
        private User $payer,

        #[OneToMany(targetEntity: User::class, mappedBy: 'expense')]
        #[JoinColumn(name: 'user_id', referencedColumnName: 'id')]
        private Collection $participants,

        #[ManyToMany(targetEntity: Group::class)]
        #[JoinColumn(name: 'group_id', referencedColumnName: 'id')]
        private Group $group,
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

    public function getPayer(): User
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

    public function getDescription(): string
    {
        return $this->description;
    }
}
