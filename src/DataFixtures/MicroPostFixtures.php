<?php

namespace App\DataFixtures;

use App\Entity\MicroPost;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class MicroPostFixtures extends Fixture
{

    public function __construct(private UserPasswordHasherInterface $userPasswordHasher) {



    }
    public function load(ObjectManager $manager): void
    {

        $user1 = new User();
        $user1->setEmail(time().'test@test.com');
        $user1->setPassword($this->userPasswordHasher->hashPassword($user1, '12345678'));
        $manager->persist($user1);
        // First Post
        $product = new MicroPost();
        $product->setTitle('First Post');
        $product->setText('This is a first post');
        $product->setAuthor($user1);
        $product->setCreated(new \DateTime());
        $manager->persist($product);

        $user2 = new User();
        $user2->setEmail(rand(1, 100).'test@test.com');
        $user2->setPassword($this->userPasswordHasher->hashPassword($user2, '12345678'));
        $manager->persist($user2);
        // Second Post
        $product = new MicroPost();
        $product->setTitle('Second Post');
        $product->setText('This is a second post');
        $product->setAuthor($user2);
        $product->setCreated(new \DateTime());
        $manager->persist($product);



        $manager->flush();
    }
}
