<?php

namespace Labstag\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Faker\Factory;
use Labstag\Entity\Template;
use Labstag\Repository\TemplateRepository;
use Twig\Environment;

class TemplateFixtures extends Fixture
{
    private const NUMBER = 10;

    /**
     * @var TemplateRepository
     */
    private $repository;

    /**
     * @var Environment
     */
    private $twig;

    public function __construct(TemplateRepository $repository, Environment $twig)
    {
        $this->twig       = $twig;
        $this->repository = $repository;
    }

    public function load(ObjectManager $manager): void
    {
        $this->add($manager);
        $this->addContactEmail($manager);
        $this->addCheckedEmail($manager);
        $this->addCheckedPhone($manager);
        $this->addLostPassword($manager);
    }

    private function addLostPassword(ObjectManager $manager): void
    {
        $template = new Template();
        $template->setName('Changement de password %site%');
        $template->setCode('lost-password');
        $template->setHtml($this->twig->render('templates/lost-password.html.twig'));
        $template->setText($this->twig->render('templates/lost-password.txt.twig'));
        $manager->persist($template);
        $manager->flush();
    }

    private function addCheckedPhone(ObjectManager $manager): void
    {
        $template = new Template();
        $template->setName('Validation du téléphone %site%');
        $template->setCode('checked-phone');
        $template->setHtml($this->twig->render('templates/checked-phone.html.twig'));
        $template->setText($this->twig->render('templates/checked-phone.txt.twig'));
        $manager->persist($template);
        $manager->flush();
    }

    private function addCheckedEmail(ObjectManager $manager): void
    {
        $template = new Template();
        $template->setName('Validation de mail %site%');
        $template->setCode('checked-mail');
        $template->setHtml($this->twig->render('templates/checked-email.html.twig'));
        $template->setText($this->twig->render('templates/checked-email.txt.twig'));
        $manager->persist($template);
        $manager->flush();
    }

    private function addContactEmail(ObjectManager $manager): void
    {
        $template = new Template();
        $template->setName('Contact %site%');
        $template->setCode('contact');
        $template->setHtml($this->twig->render('templates/contact-email.html.twig'));
        $template->setText($this->twig->render('templates/contact-email.txt.twig'));
        $manager->persist($template);
        $manager->flush();
    }

    private function add(ObjectManager $manager): void
    {
        $faker = Factory::create('fr_FR');
        for ($index = 0; $index < self::NUMBER; ++$index) {
            $template = new Template();
            $template->setName($faker->unique()->colorName);
            $template->setCode($faker->unique()->word);
            /** @var string $content */
            $content = $faker->unique()->paragraphs(10, true);
            $template->setHtml($content);
            $template->setText($content);
            $manager->persist($template);
        }

        $manager->flush();
    }
}
