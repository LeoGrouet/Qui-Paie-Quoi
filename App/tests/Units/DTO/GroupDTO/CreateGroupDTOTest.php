<?php

namespace App\tests\Units\DTO;

use App\DTO\GroupDTO;
use App\DTO\GroupDTO\CreateGroupDTO;
use App\Entity\User;
use Doctrine\Common\Collections\ArrayCollection;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Validator\Constraints\Collection;

class CreateGroupDTOTest extends TestCase
{
    public function testGroupDTOName(): void
    {
        $groupDTO = new CreateGroupDTO();
        $groupDTO->setName('Group Name');
        $this->assertSame('Group Name', $groupDTO->getName());
    }

    public function testGroupDTODescription(): void
    {
        $groupDTO = new CreateGroupDTO();
        $groupDTO->setDescription('Description');
        $this->assertSame('Description', $groupDTO->getDescription());
    }

    public function testGroupDTOUsers(): void
    {
        $user = new User('New user', 'newuser@gmail.com');
        $user1 = new User('New user1', 'newuser1@gmail.com');
        $users = new ArrayCollection([$user, $user1]);

        $groupDTO = new CreateGroupDTO();
        $groupDTO->setUsers($users);
        $this->assertSame($users, $groupDTO->getUsers());
    }
}
