<?php

namespace App\Entity;

use App\Repository\ExpenseRepository;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\JoinColumn;
use Doctrine\ORM\Mapping\ManyToOne;
use Doctrine\ORM\Mapping\OneToMany;

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

        #[ManyToOne(targetEntity: User::class, inversedBy: 'expense')]
        #[JoinColumn(name: 'user_id', referencedColumnName: 'id')]
        // Une dépense possède un seul $payer(user)
        // Mais plusieurs depense peuvent être du meme payer
        private User $payer,

        #[OneToMany(targetEntity: User::class, mappedBy: 'expenses')]
        #[JoinColumn(name: 'user_id', referencedColumnName: 'id')]
        // une dépense possède un ou plusieurs participants
        private Collection $participants,

        #[ManyToOne(targetEntity: Group::class)]
        #[JoinColumn(name: 'group_id', referencedColumnName: 'id')]
        // une dépense appartient a un seul group
        // Plusieurs dépense appartiennent au meme groupe
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
    public function getParticipants(): Collection
    {
        return $this->participants;
    }

    public function getDescription(): string
    {
        return $this->description;
    }
}
