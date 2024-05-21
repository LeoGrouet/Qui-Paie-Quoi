<?php

namespace App\Entity;

use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\JoinTable;
use Doctrine\ORM\Mapping\ManyToMany;

#[ORM\Entity]
class User
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private int $id;

    public function __construct(
        #[ORM\Column(type: 'string', length: 60)]
        private string $name,

        #[ORM\Column(type: "string", length: 60)]
        private string $email,

        #[ORM\Column(type: "string", length: 60)]
        private string $password,

        #[ManyToMany(targetEntity: Group::class, inversedBy: 'users')]
        #[JoinTable(name: 'users_groups')]
        private Collection $groups
    ) {
    }

    public function getName(): string
    {
        return $this->name;
    }
}
