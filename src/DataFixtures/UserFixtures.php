<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class UserFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        // Create user instances
        $user1 = new User();
        $user1->setUsername('john_doe');
        $manager->persist($user1);

        $user2 = new User();
        $user2->setUsername('jane_doe');
        $manager->persist($user2);

        // Flush to save users to database
        $manager->flush();
        
        // Reference users for other fixtures
        $this->addReference('user-john', $user1);
        echo "User-john reference set.\n";
        $this->addReference('user-jane', $user2);
    }
}