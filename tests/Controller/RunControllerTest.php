<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;


class RunControllerTest extends WebTestCase
{
    public function testListRuns()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/runs');

        $this->assertResponseIsSuccessful();
    }
}