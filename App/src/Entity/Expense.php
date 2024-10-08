<?php

namespace App\Entity;

use App\Repository\ExpenseRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\JoinColumn;
use Doctrine\ORM\Mapping\JoinTable;
use Doctrine\ORM\Mapping\ManyToMany;
use Doctrine\ORM\Mapping\ManyToOne;
use Symfony\Component\Serializer\Annotation\Groups;

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

        /**
         * @var Collection<int, User>
         */
        #[ManyToMany(targetEntity: User::class, inversedBy: 'expenses')]
        #[JoinTable(name: 'expenses_users')]
        #[Groups(['expense.participants'])]
        private Collection $participants,

        #[ManyToOne(targetEntity: Group::class, inversedBy: 'expenses')]
        #[JoinColumn(name: 'group_id', referencedColumnName: 'id')]
        private Group $group,
    ) {
        $participants = new ArrayCollection();
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getAmount(): int
    {
        return $this->amount;
    }

    public function setAmount(int $amount): void
    {
        $this->amount = $amount;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function setDescription(string $description): void
    {
        $this->description = $description;
    }

    public function getPayer(): User
    {
        return $this->payer;
    }

    public function setPayer(User $payer): void
    {
        $this->payer = $payer;
    }

    /**
     * @return Collection<int, User>
     */
    public function getParticipants(): Collection
    {
        return $this->participants;
    }

    public function setParticipants(Collection $participants): void
    {
        $this->participants = $participants;
    }

    public function getGroup(): Group
    {
        return $this->group;
    }
}
