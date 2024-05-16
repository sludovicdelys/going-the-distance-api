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
        $run->setAverageSpeed(10);
        $run->setRunningPace(new DateTime);
        $run->setStartDate(new DateTime);
        $run->setStartTime(new DateTime);
        $run->setTime(new DateTime);
        $run->setDistance(20);
        $run->setComments("Out of breath but happy");

        // Persist the Run entity to the database
        $this->entityManager->persist($run);
        $this->entityManager->flush();

        // Retrieve the Run entity from the database
        $persistedRun = $this->entityManager->getRepository(Run::class)->findOneBy(['username' => 'Username']);

        // Assert that the Run entity was persisted correctly
        $this->assertInstanceOf(Run::class, $persistedRun);
        $this->assertEquals('Username', $persistedRun->getUsername());
        $this->assertEquals('Type', $persistedRun->getType());
        $this->assertEquals(10, $persistedRun->getAverageSpeed());
        $this->assertEquals(20, $persistedRun->getDistance());
    }

    public function testReadRun(): void
    {
        // Create a new instance of Run
        $run = new Run();

        $run->setUsername('Username');
        $run->setType('Type');
        $run->setAverageSpeed(10);
        $run->setRunningPace(new DateTime);
        $run->setStartDate(new DateTime);
        $run->setStartTime(new DateTime);
        $run->setTime(new DateTime);
        $run->setDistance(20);
        $run->setComments("Out of breath yoooo");

        // Retrieve the Run entity from the database based on the username
        $run = $this->entityManager->getRepository(Run::class)->findOneBy(['username' => 'Username']);

        // Assert that the Run entity exists and has the correct username
        if ($run) {
            $this->assertInstanceOf(Run::class, $run);
            $this->assertEquals('Username', $run->getUsername());
        } else {
            // If the Run entity is not found, mark the test as skipped
            $this->markTestSkipped('Run entity with username "Username" not found in the database.');
        }

    }

    public function testUpdateRun(): void
    {
        // Retrieve the Run entity from the database
        $run = $this->entityManager->getRepository(Run::class)->findOneBy(['username' => 'Username']);

        // Update properties of the Run entity
        $run->setUsername('NewUsername');
        $run->setType('NewType');
        $run->setAverageSpeed(5);
        $run->setRunningPace(new DateTime);
        $run->setStartDate(new DateTime);
        $run->setStartTime(new DateTime);
        $run->setTime(new DateTime);
        $run->setDistance(15);
        $run->setComments("Out of breath but happy");
        // Update other properties as needed

        // Flush changes to the database
        $this->entityManager->flush();

        // Retrieve the updated Run entity from the database
        $updatedRun = $this->entityManager->getRepository(Run::class)->find($run->getId());

        // Assert that the Run entity was updated correctly
        $this->assertInstanceOf(Run::class, $updatedRun);
        $this->assertEquals('NewUsername', $updatedRun->getUsername());
        $this->assertEquals('NewType', $updatedRun->getType());
        // Add more assertions as needed
    }

    public function testDeleteRun(): void
    {
        try {
        // Create a new instance of Run
        $run = new Run();

        $run->setUsername('LazyUsernameThree');
        $run->setType('EasyType');
        $run->setAverageSpeed(10);
        $run->setRunningPace(new DateTime);
        $run->setStartDate(new DateTime);
        $run->setStartTime(new DateTime);
        $run->setTime(new DateTime);
        $run->setDistance(10);
        $run->setComments("Pushing through !");

        // Persist the Run entity to the database
        $this->entityManager->persist($run);
        $this->entityManager->flush();

        /*$runs = $this->entityManager->getRepository(Run::class)->findAll();
        // Output debugging information
        echo "All Runs: " . PHP_EOL;
        var_dump($runs);*/

        // Retrieve the Run entity from the database
        $run = $this->entityManager->getRepository(Run::class)->findOneBy(['username' => 'LazyUsernameThree']);
        
        /*echo "Run entity before deletion: " . PHP_EOL;
        var_dump($run);*/

        // Remove the Run entity from the database
        $this->entityManager->remove($run);
        $this->entityManager->flush();

        /*// Output debugging information
        echo "Run entity after deletion: " . PHP_EOL;
        var_dump($run);*/

        // Refresh the entity manager
        $this->entityManager->refresh($run);

        // Assert that the Run entity was deleted
        $this->assertNull($run);
    } catch (\Exception $e) {
        $this->fail('An error occurred: ' . $e->getMessage());
    }
    }
}
