<?php

namespace App\Entity;

use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\ManyToMany;
use Doctrine\ORM\Mapping\OneToMany;

#[ORM\Entity]
class Group
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private int $id;

    public function __construct(
        #[ORM\Column(type: 'string', length: 60)]
        private int $name,

        #[ORM\Column(type: 'string', length: 180)]
        private string $description,

        #[ORM\Column(type: 'object')]
        private Bilan $bilan,

        #[ManyToMany(targetEntity: User::class, mappedBy: 'groups')]
        private Collection $users,

        #[OneToMany(targetEntity: Expense::class, mappedBy: 'group')]
        private Collection $expenses
    ) {
    }
}
