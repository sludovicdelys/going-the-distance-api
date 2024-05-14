<?php

namespace App\Tests\Unit;

use DateTime;
use App\Entity\Run;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;


class RunTest extends KernelTestCase
{
    private EntityManagerInterface $entityManager;

    protected function setUp(): void
    {
        $kernel = self::bootKernel();
        $this->entityManager = $kernel->getContainer()->get('doctrine')->getManager();
    }

    public function testCreateRun(): void
    {
        // Create a new instance of Run
        $run = new Run();

        $run->setUsername('Username');
        $run->setType('Type');
        $run->setAverageSpeed(9);
        $run->setRunningPace(new DateTime);
        $run->setStartDate(new DateTime);
        $run->setStartTime(new DateTime);
        $run->setTime(new DateTime);
        $run->setDistance(10);
        $run->setComments("Out of breath but happy");

        // Persist the Run entity to the database
        $this->entityManager->persist($run);
        $this->entityManager->flush();

        // Retrieve the Run entity from the database
        $persistedRun = $this->entityManager->getRepository(Run::class)->find($run->getId());

        // Assert that the Run entity was persisted correctly
        $this->assertInstanceOf(Run::class, $persistedRun);
        $this->assertEquals('Username', $persistedRun->getUsername());
        $this->assertEquals('Type', $persistedRun->getType());
        $this->assertEquals(9, $persistedRun->getAverageSpeed());
        $this->assertEquals(10, $persistedRun->getDistance());
    }
}
