<?php

namespace Labstag\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Faker\Factory;
use Faker\Generator;
use Labstag\Entity\Tag;
use Labstag\Repository\TagRepository;

class TagFixtures extends Fixture
{
    private const NUMBER = 10;

    /**
     * @var TagRepository
     */
    private $repository;

    public function __construct(TagRepository $repository)
    {
        $this->repository = $repository;
    }

    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create('fr_FR');
        $this->add($manager, 'post', $faker);
        $this->add($manager, 'bookmark', $faker);
        $this->delete($manager);
    }

    private function add(ObjectManager $manager, string $type, Generator &$faker): void
    {
        for ($index = 0; $index < self::NUMBER; ++$index) {
            $tags = new Tag();
            $tags->setType($type);
            $tags->setName($faker->unique()->colorName);
            $manager->persist($tags);
        }

        $manager->flush();
    }

    private function delete(ObjectManager $manager): void
    {
        /** @var array $tags */
        $tags = $this->repository->findAll();
        /** @var int $tabIndex */
        $tabIndex = array_rand($tags, 1);
        $manager->remove($tags[$tabIndex]);
        $manager->flush();
    }
}
