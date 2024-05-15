<?php

namespace App\DataFixtures;

use DateTime;
use App\Entity\Run;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;

class RunFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $run = new Run();
        $run->setUsername('Username');
        $run->setType('Type');
        $run->setAverageSpeed(9);
        $run->setRunningPace(new DateTime);
        $run->setStartDate(new DateTime);
        $run->setStartTime(new DateTime);
        $run->setTime(new DateTime);
        $run->setDistance(10);
        $run->setComments("Out of breath STILL Happy !");

        $manager->persist($run);

        $manager->flush();
    }
}
