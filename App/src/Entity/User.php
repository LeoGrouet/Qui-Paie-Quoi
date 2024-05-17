<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

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
    ) {
    }
}
