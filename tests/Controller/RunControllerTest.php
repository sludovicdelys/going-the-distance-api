<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;


class RunControllerTest extends WebTestCase
{
    /*public function testListRuns()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/runs');

        $this->assertResponseIsSuccessful();
    }*/

    public function read(): void
    {
        $client = static::createClient();
        // Make a GET request to the endpoint for reading a specific run
        $client->request('GET', '/runs/1');
        // Assert that the response is successful (HTTP status code 200)
        $this->assertResponseIsSuccessful();
        // Add more assertions as needed to validate the retrieved run
    }
}