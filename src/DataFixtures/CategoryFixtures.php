<?php

namespace Labstag\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Faker\Factory;
use Labstag\Entity\Category;
use Labstag\Repository\CategoryRepository;

class CategoryFixtures extends Fixture
{
    private const NUMBER = 10;

    public function __construct(CategoryRepository $repository)
    {
        $this->repository = $repository;
    }

    public function load(ObjectManager $manager)
    {
        $this->add($manager);
        $this->delete($manager);
    }

    private function add(ObjectManager $manager)
    {
        $faker = Factory::create('fr_FR');
        for ($index = 0; $index < self::NUMBER; ++$index) {
            $category = new Category();
            $category->setName($faker->unique()->colorName);
            $manager->persist($category);
        }

        $manager->flush();
    }

    private function delete(ObjectManager $manager)
    {
        $category = $this->repository->findAll();
        $tabIndex = array_rand($category, 1);
        $manager->remove($category[$tabIndex]);
        $manager->flush();
    }
}
