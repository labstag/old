<?php

namespace Labstag\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Faker\Factory;
use Labstag\Entity\Category;

class CategoryFixtures extends Fixture
{
    private const NUMBER = 10;

    public function load(ObjectManager $manager)
    {
        $this->add($manager);
    }

    private function add(ObjectManager $manager)
    {
        $faker = Factory::create('fr_FR');
        for ($i = 0; $i < self::NUMBER; ++$i) {
            $category = new Category();
            $category->setName($faker->unique()->colorName);
            $manager->persist($category);
        }

        $manager->flush();
    }
}
