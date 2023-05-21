<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class Users extends Fixture
{


    public function __construct(private UserPasswordHasherInterface $userPasswordHasher) {



    }

    public function load(ObjectManager $manager): void
    {
        $user1 = new User();
        $user1->setEmail('test@test.com');
        $user1->setPassword($this->userPasswordHasher->hashPassword($user1, '12345678'));
        $manager->persist($user1);

        $user2 = new User();
        $user2->setEmail("test2@test.com");
        $user2->setPassword($this->userPasswordHasher->hashPassword($user2, '12345678'));
        $manager->persist($user2);

        $manager->flush();
    }
}
