<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class GetRunsByUserControllerTest extends WebTestCase
{
    public function testGetUserRunsSuccess()
    {
        $client = static::createClient();
        $client->request('GET', '/users/1/runs');

        $this->assertResponseIsSuccessful();
        $this->assertJson($client->getResponse()->getContent());
    }

}