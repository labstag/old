<?php

namespace Labstag\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Exception;
use Faker\Factory;
use Labstag\Entity\Bookmark;
use Labstag\Repository\TagsRepository;
use Labstag\Repository\UserRepository;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class BookmarkFixtures extends Fixture implements DependentFixtureInterface
{
    private const NUMBER = 25;

    /**
     * @var UserRepository
     */
    private $userRepository;

    /**
     * @var TagsRepository
     */
    private $tagsRepository;

    public function __construct(UserRepository $userRepository, TagsRepository $tagsRepository)
    {
        $this->userRepository = $userRepository;
        $this->tagsRepository = $tagsRepository;
    }

    public function load(ObjectManager $manager)
    {
        $this->add($manager);
    }

    public function getDependencies()
    {
        return [
            FilesFixtures::class,
            TagsFixtures::class,
            UserFixtures::class,
        ];
    }

    private function add(ObjectManager $manager)
    {
        $users = $this->userRepository->findAll();
        $tags  = $this->tagsRepository->findBy(['type' => 'bookmark']);
        $faker = Factory::create('fr_FR');
        for ($index = 0; $index < self::NUMBER; ++$index) {
            $bookmark = new Bookmark();
            $bookmark->setUrl($faker->unique()->url);
            $bookmark->setName($faker->unique()->text(rand(5, 50)));
            $bookmark->setContent($faker->unique()->paragraphs(4, true));
            $user = rand(0, 1);
            if ($user) {
                $tabIndex = array_rand($users);
                $bookmark->setRefuser($users[$tabIndex]);
            }

            $this->addTags($bookmark, $tags);

            try {
                $image   = $faker->unique()->imageUrl(1920, 1920);
                $content = file_get_contents($image);
                $tmpfile = tmpfile();
                $data    = stream_get_meta_data($tmpfile);
                file_put_contents($data['uri'], $content);
                $file = new UploadedFile(
                    $data['uri'],
                    'image.jpg',
                    filesize($data['uri']),
                    null,
                    true
                );

                $bookmark->setImageFile($file);
            } catch (Exception $exception) {
            }

            $manager->persist($bookmark);
        }

        $manager->flush();
    }

    private function addTags($bookmark, $tags)
    {
        $nbr = rand(0, count($tags));
        if (0 == $nbr) {
            return;
        }

        $tabIndex = array_rand(
            $tags,
            $nbr
        );
        if (is_array($tabIndex)) {
            foreach ($tabIndex as $indendexndex) {
                $bookmark->addTag($tags[$indendexndex]);
            }

            return;
        }

        $bookmark->addTag($tags[$tabIndex]);
    }
}
