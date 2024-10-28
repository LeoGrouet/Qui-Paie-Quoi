<?php

namespace App\tests\Units\DTO;

use App\DTO\GroupDTO\UpdateGroupDTO;
use App\Entity\Group;
use App\Entity\User;
use Doctrine\Common\Collections\ArrayCollection;
use PHPUnit\Framework\TestCase;

class UpdateGroupDTOTest extends TestCase
{
    public function testUpdateGroupDTOName(): void
    {
        $groupDTO = new UpdateGroupDTO('Group Name', 'Description', new ArrayCollection());
        $this->assertSame('Group Name', $groupDTO->getName());
    }

    public function testUpdateGroupDTODescription(): void
    {
        $groupDTO = new UpdateGroupDTO('Group Name', 'Description', new ArrayCollection());
        $this->assertSame('Description', $groupDTO->getDescription());
    }

    public function testUpdateGroupDTOUsers(): void
    {
        $user = new User('user', 'newuser@gmail.com');
        $user1 = new User('user1', 'newusertest@gmail.com');

        $groupDTO = new UpdateGroupDTO('Group Name', 'Description', new ArrayCollection([$user, $user1]));
        $this->assertSame([$user, $user1], $groupDTO->getUsers());
    }
}
