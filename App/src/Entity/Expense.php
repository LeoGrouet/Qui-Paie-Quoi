<?php

namespace App\Entity;

use App\Repository\ExpenseRepository;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\InverseJoinColumn;
use Doctrine\ORM\Mapping\JoinColumn;
use Doctrine\ORM\Mapping\JoinTable;
use Doctrine\ORM\Mapping\ManyToMany;
use Doctrine\ORM\Mapping\ManyToOne;

#[ORM\Entity(repositoryClass: ExpenseRepository::class)]
class Expense
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private int $id;
    /**
     * @param Collection<User> $participants
     */
    public function __construct(
        #[ORM\Column(type: 'integer')]
        private int $amount,

        #[ORM\Column(type: 'string')]
        private string $description,

        #[ManyToOne(targetEntity: User::class, inversedBy: 'expenses')]
        private User $payer,

        #[JoinTable(name: 'expenses_participants')]
        #[JoinColumn(name: 'expense_id', referencedColumnName: 'id')]
        #[InverseJoinColumn(name: 'user_id', referencedColumnName: 'id')]
        #[ManyToMany(targetEntity: 'User')]
        private Collection|null $participants = null,

        #[ManyToOne(targetEntity: Group::class, inversedBy: 'expenses')]
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
     * @return Collection<User>
     */
    public function getParticipants(): Collection
    {
        return $this->participants;
    }

    public function getDescription(): string
    {
        return $this->description;
    }
}
