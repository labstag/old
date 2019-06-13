<?php

namespace Labstag\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Faker\Factory;
use Labstag\Entity\Post;
use Labstag\Repository\CategoryRepository;
use Labstag\Repository\TagsRepository;
use Labstag\Repository\UserRepository;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class PostFixtures extends Fixture implements DependentFixtureInterface
{
    private const NUMBER = 25;

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

    private function add(ObjectManager $manager)
    {
        $users      = $this->userRepository->findAll();
        $categories = $this->categoryRepository->findAll();
        $tags       = $this->tagsRepository->findAll();
        $faker      = Factory::create('fr_FR');
        for ($index = 0; $index < self::NUMBER; ++$index) {
            $post = new Post();
            $post->setName($faker->unique()->text(rand(5, 50)));
            $post->setContent($faker->unique()->paragraphs(4, true));
            $post->setRefuser($users[array_rand($users)]);
            $post->setRefcategory($categories[array_rand($categories)]);
            $this->addTags($post, $tags);
            $addImage = rand(0, 1);
            if (1 === $addImage) {
                $image   = $faker->unique()->imageUrl(1920, 1920);
                $image = str_replace('lorempixel.com', 'picsum.photos', $image);
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

                $post->setImageFile($file);
            }

            $manager->persist($post);
        }

        $manager->flush();
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
