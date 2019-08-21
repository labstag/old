<?php

namespace Labstag\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Faker\Factory;
use Labstag\Entity\Templates;
use Labstag\Repository\TemplatesRepository;
use Twig\Environment;

class TemplatesFixtures extends Fixture
{
    private const NUMBER = 10;

    /**
     * @var TemplatesRepository
     */
    private $repository;

    /**
     * @var Environment
     */
    private $twig;

    public function __construct(TemplatesRepository $repository, Environment $twig)
    {
        $this->twig       = $twig;
        $this->repository = $repository;
    }

    public function load(ObjectManager $manager)
    {
        $this->add($manager);
        $this->addEmail($manager);
    }

    private function addEmail(ObjectManager $manager)
    {
        $templates = new Templates();
        $templates->setName('Contact %site%');
        $templates->setCode('contact');
        $templates->setContent($this->twig->render('templates/email.html.twig'));
        $manager->persist($templates);
        $manager->flush();
    }

    private function add(ObjectManager $manager)
    {
        $faker = Factory::create('fr_FR');
        for ($index = 0; $index < self::NUMBER; ++$index) {
            $templates = new Templates();
            $templates->setName($faker->unique()->colorName);
            $templates->setCode($faker->unique()->word);
            $templates->setContent($faker->unique()->paragraphs(10, true));
            $manager->persist($templates);
        }

        $manager->flush();
    }
}
