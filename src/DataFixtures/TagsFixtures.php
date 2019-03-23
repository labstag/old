<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use App\Entity\Tags;
use Faker\Factory;

class TagsFixtures extends Fixture
{

    public function load(ObjectManager $manager)
    {
        $faker = Factory::create('fr_FR');
        for ($i = 0; $i < 10; ++$i) {
            $tags = new Tags();
            $tags->setName($faker->unique()->safeColorName);
            $manager->persist($tags);
        }

        $manager->flush();
    }
}
