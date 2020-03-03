<?php

namespace Labstag\DataFixtures;

use bheller\ImagesGenerator\ImagesGeneratorProvider;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Exception;
use Faker\Factory;
use finfo;
use Labstag\Entity\Post;
use Labstag\Repository\CategoryRepository;
use Labstag\Repository\TagRepository;
use Labstag\Repository\UserRepository;
use Psr\Log\LoggerInterface;
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
     * @var TagRepository
     */
    private $tagsRepository;

    /**
     * @var LoggerInterface
     */
    private $logger;

    public function __construct(
        UserRepository $userRepository,
        CategoryRepository $categoryRepository,
        TagRepository $tagsRepository,
        LoggerInterface $logger
    )
    {
        $this->logger             = $logger;
        $this->userRepository     = $userRepository;
        $this->categoryRepository = $categoryRepository;
        $this->tagsRepository     = $tagsRepository;
    }

    public function load(ObjectManager $manager): void
    {
        $this->add($manager);
    }

    public function getDependencies(): array
    {
        return [
            FilesFixtures::class,
            TagFixtures::class,
            CategoryFixtures::class,
            UserFixtures::class,
        ];
    }

    private function add(ObjectManager $manager): void
    {
        $users      = $this->userRepository->findAll();
        $categories = $this->categoryRepository->findAll();
        $tags       = $this->tagsRepository->findBy(['type' => 'post']);
        $faker      = Factory::create('fr_FR');
        $faker->addProvider(new ImagesGeneratorProvider($faker));
        /** @var resource $finfo */
        $finfo   = finfo_open(FILEINFO_MIME_TYPE);
        $maxDate = $faker->unique()->dateTimeInInterval('now', '+30 years');
        for ($index = 0; $index < self::NUMBER; ++$index) {
            $post     = new Post();
            $createAt = $faker->unique()->dateTime($maxDate);
            $post->setCreatedAt($createAt);
            $post->setName($faker->unique()->text(rand(5, 50)));
            /** @var string $content */
            $content = $faker->unique()->paragraphs(4, true);
            $post->setContent($content);
            $tabIndex = array_rand($users);
            $post->setRefuser($users[$tabIndex]);
            $post->setRefcategory($categories[array_rand($categories)]);
            $this->addTag($post, $tags);
            $post->setEnable((bool) rand(0, 1));
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

                $post->setImageFile($file);
            } catch (Exception $exception) {
                $this->logger->error($exception->getMessage());
                echo $exception->getMessage();
            }

            $manager->persist($post);
        }

        $manager->flush();
    }

    private function addTag(Post $post, array $tags): void
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
