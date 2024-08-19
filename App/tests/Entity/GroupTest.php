<?php

namespace Tests\Entity;

use App\Entity\Group;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use PHPUnit\Framework\TestCase;

class GroupTest extends TestCase
{
    public function testGroupConstructor(): void
    {
        $group = new Group('Group Name', 'Description', new ArrayCollection([]));

        $this->assertSame('Group Name', $group->getName());
        $this->assertSame('Description', $group->getDescription());
        $this->assertInstanceOf(Collection::class, $group->getUsers());
        $this->assertEmpty($group->getUsers());
    }
}
