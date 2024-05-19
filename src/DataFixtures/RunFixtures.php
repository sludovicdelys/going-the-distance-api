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
        // Check for user-john reference
        if ($this->hasReference('user-john')) {
            echo "Reference user-john found.\n";
            $userJohn = $this->getReference('user-john');
        } else {
            echo "Reference user-john NOT found.\n";
            return; // Exit or handle the error
        }

        // Check for user-jane reference
        if ($this->hasReference('user-jane')) {
            echo "Reference user-jane found.\n";
            $userJane = $this->getReference('user-jane');
        } else {
            echo "Reference user-jane NOT found.\n";
            return; // Exit or handle the error
        }

        // Define multiple run data for user-john
        $johnRuns = [
            [
                'distance' => 5000,
                'time' => new \DateTime('01:00:00'),
                'runningPace' => new \DateTime('00:05:00'),
                'type' => 'Morning Run',
                'averageSpeed' => 10,
                'startDate' => new \DateTime('today'),
                'startTime' => new \DateTime('08:00:00'),
                'comments' => 'Nice weather',
            ],
            [
                'distance' => 10000,
                'time' => new \DateTime('02:00:00'),
                'runningPace' => new \DateTime('00:06:00'),
                'type' => 'Evening Run',
                'averageSpeed' => 9,
                'startDate' => new \DateTime('yesterday'),
                'startTime' => new \DateTime('18:00:00'),
                'comments' => 'Challenging run',
            ],
            // Add more run data as needed
        ];

        // Define multiple run data for user-jane
        $janeRuns = [
            [
                'distance' => 7000,
                'time' => new \DateTime('01:20:00'),
                'runningPace' => new \DateTime('00:07:00'),
                'type' => 'Afternoon Run',
                'averageSpeed' => 8,
                'startDate' => new \DateTime('today'),
                'startTime' => new \DateTime('14:00:00'),
                'comments' => 'Sunny day',
            ],
            [
                'distance' => 12000,
                'time' => new \DateTime('01:50:00'),
                'runningPace' => new \DateTime('00:06:30'),
                'type' => 'Night Run',
                'averageSpeed' => 9,
                'startDate' => new \DateTime('yesterday'),
                'startTime' => new \DateTime('20:00:00'),
                'comments' => 'Cool breeze',
            ],
            // Add more run data as needed
        ];

        // Loop through run data for user-john and create Run entities
        foreach ($johnRuns as $runData) {
            $run = new Run();
            $run->setUser($userJohn);
            $run->getUsername($userJohn);
            $run->setDistance($runData['distance']);
            $run->setTime($runData['time']);
            $run->setRunningPace($runData['runningPace']);
            $run->setType($runData['type']);
            $run->setAverageSpeed($runData['averageSpeed']);
            $run->setStartDate($runData['startDate']);
            $run->setStartTime($runData['startTime']);
            $run->setComments($runData['comments']);

            $manager->persist($run);
        }

        // Loop through run data for user-jane and create Run entities
        foreach ($janeRuns as $runData) {
            $run = new Run();
            $run->setUser($userJane);
            $run->getUsername($userJane);
            $run->setDistance($runData['distance']);
            $run->setTime($runData['time']);
            $run->setRunningPace($runData['runningPace']);
            $run->setType($runData['type']);
            $run->setAverageSpeed($runData['averageSpeed']);
            $run->setStartDate($runData['startDate']);
            $run->setStartTime($runData['startTime']);
            $run->setComments($runData['comments']);

            $manager->persist($run);
        }

        $manager->flush();
    }

    public function getDependencies()
    {
        return [
            UserFixtures::class,
        ];
    }
}