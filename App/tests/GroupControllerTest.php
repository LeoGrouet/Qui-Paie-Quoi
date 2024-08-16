<?php

namespace App\Tests;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class GroupControllerTest extends WebTestCase
{
    public function testGroupsPage(): void
    {
        $client = static::createClient();

        $client->request('GET', '/groups');

        $this->assertResponseIsSuccessful();
    }
}
