<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;


class SortieControllerTest extends WebTestCase
{
    public function testListRuns()
    {
        $client = static::createClient();

        $client->request('GET', '/runs');

        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('h1', 'Liste des sorties'); 
    }
}