<?php

namespace Labstag\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Faker\Factory;
use Labstag\Entity\Chapitre;
use Labstag\Repository\HistoryRepository;

class ChapitreFixtures extends Fixture implements DependentFixtureInterface
{
    private const NUMBER = 50;

    /**
     * @var HistoryRepository
     */
    private $historyRepository;

    public function __construct(HistoryRepository $historyRepository)
    {
        $this->historyRepository = $historyRepository;
    }

    public function load(ObjectManager $manager)
    {
        $this->add($manager);
    }

    public function getDependencies()
    {
        return [
            HistoryFixtures::class,
        ];
    }

    private function add(ObjectManager $manager)
    {
        $histoires = $this->historyRepository->findAll();
        $faker     = Factory::create('fr_FR');
        for ($index = 0; $index < self::NUMBER; ++$index) {
            $chapitre = new Chapitre();
            $chapitre->setName($faker->unique()->sentence);
            $enable = rand(0, 1);
            $chapitre->setEnable($enable);
            $tabIndex = array_rand($histoires);
            $histoire = $histoires[$tabIndex];
            $chapitre->setRefhistory($histoire);
            $enable = rand(0, 1);
            $chapitre->setEnable($enable);
            $chapitre->setContent($faker->unique()->paragraphs(10, true));
            $manager->persist($chapitre);
            $manager->flush();
        }
    }
}
