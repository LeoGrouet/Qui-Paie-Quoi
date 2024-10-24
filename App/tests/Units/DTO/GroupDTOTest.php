<?php

namespace Tests\Units\DTO;

use App\DTO\GroupDTO;
use PHPUnit\Framework\TestCase;

class GroupDTOTest extends TestCase
{
    public function testGroupDTOName(): void
    {
        $groupDTO = new GroupDTO();
        $groupDTO->setName('Group Name');
        $this->assertSame('Group Name', $groupDTO->getName());
    }

    public function testGroupDTODescription(): void
    {
        $groupDTO = new GroupDTO();
        $groupDTO->setDescription('Description');
        $this->assertSame('Description', $groupDTO->getDescription());
    }
}
