<?php

namespace App\Entity;

use App\Repository\ExpenseRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\InverseJoinColumn;
use Doctrine\ORM\Mapping\JoinColumn;
use Doctrine\ORM\Mapping\JoinTable;
use Doctrine\ORM\Mapping\ManyToMany;
use Doctrine\ORM\Mapping\ManyToOne;
use Doctrine\ORM\Mapping\OneToMany;

#[ORM\Entity(repositoryClass: ExpenseRepository::class)]
class Expense
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private int $id;
    #[ORM\Column(type: 'integer')]
    private int $amount;

    #[ORM\Column(type: 'string')]
    private string $description;

    #[ManyToOne(targetEntity: User::class, inversedBy: 'expenses')]
    #[JoinColumn(name: 'payer', referencedColumnName: 'id')]
    private User|null $payer = null;
    /**
     * @param Collection<User> $participants
     */
    #[JoinTable(name: 'expenses_users')]
    #[JoinColumn(name: 'expense_id', referencedColumnName: 'id')]
    #[InverseJoinColumn(name: 'user_id', referencedColumnName: 'id', unique: true)]
    #[ManyToMany(targetEntity: 'User')]
    private Collection $participants;

    #[ManyToOne(targetEntity: Group::class, inversedBy: 'expenses')]
    #[JoinColumn(name: 'group_id', referencedColumnName: 'id')]
    private Group|null $group = null;

    public function __construct()
    {
        $this->participants = new ArrayCollection();
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
