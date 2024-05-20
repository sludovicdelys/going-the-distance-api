<?php

namespace App\DataFixtures;

use App\Entity\User;
use App\Entity\Run;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create();

        // Create users
        for ($i = 0; $i < 10; $i++) {
            $user = new User();
            $user->setUsername($faker->userName);
            $manager->persist($user);

            // Create runs for each user
            for ($j = 0; $j < 2; $j++) {
                $run = new Run();
                $run->setUser($user);
                $run->setType($faker->randomElement(['Training', 'Race', 'Leisure', 'Interval', 'Hill', 'Recovery']));
                $run->setDistance($faker->randomFloat(2, 5, 20));
                $run->setTime((new \DateTime())->setTimestamp($faker->dateTimeBetween('-2 hours', '-30 minutes')->getTimestamp())->setDate(1970, 1, 1));
                $run->setStartDate((new \DateTime())->setTimestamp($faker->unixTime())->setTime(0, 0, 0));
                $run->setStartTime((new \DateTime())->setTimestamp($faker->dateTimeThisYear()->getTimestamp())->setDate(1970, 1, 1));
                $run->setComments($faker->sentence());

                // Calculate average speed and running pace
                $run->calculateAverageSpeed();
                $run->calculateRunningPace();

                $manager->persist($run);
            }
        }

        $manager->flush();
    }
}