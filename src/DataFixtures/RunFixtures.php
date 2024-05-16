<?php

namespace App\DataFixtures;


use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use App\Entity\Run;

class RunFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        if ($this->hasReference('user-john')) {
            echo "Reference user-john found.\n";
            $userJohn = $this->getReference('user-john');
        } else {
            echo "Reference user-john NOT found.\n";
            return; // Exit or handle the error
        }

        $run1 = new Run();
        $run1->setUser($userJohn);
        $run1->setUsername($userJohn->getUsername());
        $run1->setDistance(5000);
        $run1->setTime(new \DateTime('01:00:00'));
        $run1->setRunningPace(new \DateTime('00:05:00')); // Set a default running pace
        $run1->setType('Morning Run');
        $run1->setAverageSpeed(10);
        $run1->setStartDate(new \DateTime('today'));
        $run1->setStartTime(new \DateTime('08:00:00'));
        $run1->setComments('Nice weather');

        $manager->persist($run1);

        $manager->flush();
    }

    public function getDependencies()
    {
        return [
            UserFixtures::class,
        ];
    }
}
