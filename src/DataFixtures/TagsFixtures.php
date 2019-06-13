<?php

namespace Labstag\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Faker\Factory;
use Labstag\Entity\Tags;
use Labstag\Repository\TagsRepository;

class TagsFixtures extends Fixture
{
    private const NUMBER = 10;

    public function __construct(TagsRepository $repository)
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
        for ($i = 0; $i < self::NUMBER; ++$i) {
            $tags = new Tags();
            $tags->setName($faker->unique()->safeColorName);
            $manager->persist($tags);
        }

        $manager->flush();
    }

    private function delete(ObjectManager $manager)
    {
        $tags     = $this->repository->findAll();
        $tabIndex = array_rand($tags, 1);
        $manager->remove($tags[$tabIndex]);
        $manager->flush();
    }
}
