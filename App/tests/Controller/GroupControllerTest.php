<?php

namespace App\Tests;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class GroupControllerTest extends WebTestCase
{
    public function testGroup(): void
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/groups');

        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('h1', 'La route fonctionne');
    }
}