<?php

namespace App\DataFixtures;

use App\Entity\MicroPost;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class MicroPostFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        // First Post
        $product = new MicroPost();
        $product->setTitle('First Post');
        $product->setText('This is a first post');
        $product->setCreated(new \DateTime());
        $manager->persist($product);

        // Second Post
        $product = new MicroPost();
        $product->setTitle('Second Post');
        $product->setText('This is a second post');
        $product->setCreated(new \DateTime());
        $manager->persist($product);



        $manager->flush();
    }
}
