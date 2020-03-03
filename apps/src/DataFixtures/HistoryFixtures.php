<?php

namespace Labstag\DataFixtures;

use bheller\ImagesGenerator\ImagesGeneratorProvider;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Exception;
use Faker\Factory;
use finfo;
use Labstag\Entity\History;
use Labstag\Repository\UserRepository;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class HistoryFixtures extends Fixture implements DependentFixtureInterface
{
    private const NUMBER = 10;

    /**
     * @var UserRepository
     */
    private $userRepository;

    /**
     * @var LoggerInterface
     */
    private $logger;

    public function __construct(UserRepository $userRepository, LoggerInterface $logger)
    {
        $this->logger         = $logger;
        $this->userRepository = $userRepository;
    }

    public function load(ObjectManager $manager): void
    {
        $this->add($manager);
    }

    public function getDependencies(): array
    {
        return [
            FilesFixtures::class,
            UserFixtures::class,
        ];
    }

    private function add(ObjectManager $manager): void
    {
        $users = $this->userRepository->findAll();
        $faker = Factory::create('fr_FR');
        $faker->addProvider(new ImagesGeneratorProvider($faker));
        /** @var resource $finfo */
        $finfo   = finfo_open(FILEINFO_MIME_TYPE);
        $maxDate = $faker->unique()->dateTimeInInterval('now', '+30 years');
        for ($index = 0; $index < self::NUMBER; ++$index) {
            $history  = new History();
            $createAt = $faker->unique()->dateTime($maxDate);
            $history->setCreatedAt($createAt);
            $history->setName($faker->unique()->safeColorName);
            $history->setResume($faker->unique()->sentence);
            $enable = (bool) rand(0, 1);
            $history->setEnable($enable);
            $tabIndex = array_rand($users);
            $history->setRefuser($users[$tabIndex]);
            $history->setCreatedBy($users[$tabIndex]);

            $end = (bool) rand(0, 1);
            $history->setEnd($end);

            try {
                $image   = $faker->imageGenerator(
                    null,
                    1920,
                    1920,
                    'jpg',
                    true,
                    $faker->word,
                    $faker->hexColor,
                    $faker->hexColor
                );
                $content = file_get_contents($image);
                /** @var resource $tmpfile */
                $tmpfile = tmpfile();
                $data    = stream_get_meta_data($tmpfile);
                file_put_contents($data['uri'], $content);
                $file = new UploadedFile(
                    $data['uri'],
                    'image.jpg',
                    (string) finfo_file($finfo, $data['uri']),
                    null,
                    true
                );

                $history->setImageFile($file);
            } catch (Exception $exception) {
                $this->logger->error($exception->getMessage());
                echo $exception->getMessage();
            }

            $manager->persist($history);
        }

        $manager->flush();
    }
}
