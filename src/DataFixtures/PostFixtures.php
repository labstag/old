<?php

namespace Labstag\DataFixtures;

use bheller\ImagesGenerator\ImagesGeneratorProvider;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Exception;
use finfo;
use Faker\Factory;
use Labstag\Entity\Post;
use Labstag\Repository\CategoryRepository;
use Labstag\Repository\TagsRepository;
use Labstag\Repository\UserRepository;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class PostFixtures extends Fixture implements DependentFixtureInterface
{
    private const NUMBER = 25;

    /**
     * @var UserRepository
     */
    private $userRepository;

    /**
     * @var CategoryRepository
     */
    private $categoryRepository;

    /**
     * @var TagsRepository
     */
    private $tagsRepository;

    public function __construct(UserRepository $userRepository, CategoryRepository $categoryRepository, TagsRepository $tagsRepository)
    {
        $this->userRepository     = $userRepository;
        $this->categoryRepository = $categoryRepository;
        $this->tagsRepository     = $tagsRepository;
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
            CategoryFixtures::class,
            UserFixtures::class,
        ];
    }

    private function add(ObjectManager $manager)
    {
        $users      = $this->userRepository->findAll();
        $categories = $this->categoryRepository->findAll();
        $tags       = $this->tagsRepository->findBy(['type' => 'post']);
        $faker      = Factory::create('fr_FR');
        $faker->addProvider(new ImagesGeneratorProvider($faker));
        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        for ($index = 0; $index < self::NUMBER; ++$index) {
            $post = new Post();
            $post->setName($faker->unique()->text(rand(5, 50)));
            $post->setContent($faker->unique()->paragraphs(4, true));
            $user = rand(0, 1);
            if ($user) {
                $tabIndex = array_rand($users);
                $post->setRefuser($users[$tabIndex]);
            }

            $post->setRefcategory($categories[array_rand($categories)]);
            $this->addTags($post, $tags);

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
                $tmpfile = tmpfile();
                $data    = stream_get_meta_data($tmpfile);
                file_put_contents($data['uri'], $content);
                $file = new UploadedFile(
                    $data['uri'],
                    'image.jpg',
                    finfo_file($finfo, $data['uri']),
                    null,
                    true
                );

                $post->setImageFile($file);
            } catch (Exception $exception) {
            }

            $manager->persist($post);
        }

        $manager->flush();
    }

    private function addTags($post, $tags)
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
                $post->addTag($tags[$indendexndex]);
            }

            return;
        }

        $post->addTag($tags[$tabIndex]);
    }
}
