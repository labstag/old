<?php

namespace Labstag\Tests\Repository;

use Doctrine\ORM\Query;
use Labstag\Entity\Category;
use Labstag\Entity\Post;
use Labstag\Entity\Tags;
use Labstag\Entity\User;
use Labstag\Lib\RepositoryTestLib;
use Labstag\Repository\CategoryRepository;
use Labstag\Repository\PostRepository;
use Labstag\Repository\TagsRepository;
use Labstag\Repository\UserRepository;

/**
 * @internal
 * @coversNothing
 */
class PostTest extends RepositoryTestLib
{

    /**
     * @var PostRepository
     */
    private $repository;

    /**
     * @var CategoryRepository
     */
    private $categoryRepository;

    /**
     * @var TagsRepository
     */
    private $tagsRepository;

    /**
     * @var UserRepository
     */
    private $userRepository;

    public function setUp(): void
    {
        parent::setUp();
        /** @var PostRepository $repository */
        $repository       = $this->entityManager->getRepository(
            Post::class
        );
        $this->repository = $repository;
        /** @var CategoryRepository $categoryRepository */
        $categoryRepository       = $this->entityManager->getRepository(
            Category::class
        );
        $this->categoryRepository = $categoryRepository;
        /** @var TagsRepository $tagsRepository */
        $tagsRepository       = $this->entityManager->getRepository(
            Tags::class
        );
        $this->tagsRepository = $tagsRepository;
        /** @var UserRepository $userRepository */
        $userRepository = $this->entityManager->getRepository(
            User::class
        );

        $this->userRepository = $userRepository;
    }

    public function testFindAll(): void
    {
        $all = $this->repository->findAll();
        $this->assertTrue(is_array($all));
    }

    public function testfindOneRandom(): void
    {
        $all = $this->repository->findAll();
        if (0 != count($all)) {
            $random = $this->repository->findOneRandom();
            $this->assertSame(get_class($random), Post::class);

            return;
        }

        $this->assertTrue(true);
    }

    public function testfindAllActiveByUser(): void
    {
        $empty = $this->repository->findAllActiveByUser(null);
        $this->AssertNull($empty);
        $user = $this->tagsRepository->findOneRandom();
        if ($user instanceof User) {
            /** @var Query $posts */
            $posts = $this->repository->findAllActiveByUser($user);
            $this->assertSame(get_class($posts), Query::class);

            return;
        }

        $this->assertTrue(true);
    }

    public function testfindAllActiveByTag(): void
    {
        $empty = $this->repository->findAllActiveByTag(null);
        $this->AssertNull($empty);
        $tags = $this->tagsRepository->findOneRandom();
        if ($tags instanceof Tags) {
            /** @var Query $posts */
            $posts = $this->repository->findAllActiveByTag($tags);
            $this->assertSame(get_class($posts), Query::class);

            return;
        }

        $this->assertTrue(true);
    }

    public function testfindAllActiveByCategory(): void
    {
        $empty = $this->repository->findAllActiveByCategory(null);
        $this->AssertNull($empty);
        $category = $this->categoryRepository->findOneRandom();
        if ($category instanceof Category) {
            /** @var Query $posts */
            $posts = $this->repository->findAllActiveByCategory($category);
            $this->assertSame(get_class($posts), Query::class);

            return;
        }

        $this->assertTrue(true);
    }

    public function testfindAllActive(): void
    {
        /** @var Query $posts */
        $posts = $this->repository->findAllActive();
        $this->assertSame(get_class($posts), Query::class);
    }
}
