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
        $this->repository         = $this->entityManager->getRepository(
            Post::class
        );
        $this->categoryRepository = $this->entityManager->getRepository(
            Category::class
        );
        $this->tagsRepository     = $this->entityManager->getRepository(
            Tags::class
        );
        $this->userRepository     = $this->entityManager->getRepository(
            User::class
        );
    }

    public function testFindAll()
    {
        $all = $this->repository->findAll();
        $this->assertTrue(is_array($all));
    }

    public function testfindOneRandom()
    {
        $all = $this->repository->findAll();
        if (0 != count($all)) {
            $random = $this->repository->findOneRandom();
            $this->assertTrue($random instanceof Post);
        }
    }

    public function testfindAllActiveByUser()
    {
        $empty = $this->repository->findAllActiveByUser(null);
        $this->AssertNull($empty);
        $user = $this->tagsRepository->findOneRandom();
        if ($user instanceof User) {
            $posts = $this->repository->findAllActiveByUser($user);
            $this->assertTrue($posts instanceof Query);
        }
    }

    public function testfindAllActiveByTag()
    {
        $empty = $this->repository->findAllActiveByTag(null);
        $this->AssertNull($empty);
        $tags = $this->tagsRepository->findOneRandom();
        if ($tags instanceof Tags) {
            $posts = $this->repository->findAllActiveByTag($tags);
            $this->assertTrue($posts instanceof Query);
        }
    }

    public function testfindAllActiveByCategory()
    {
        $empty = $this->repository->findAllActiveByCategory(null);
        $this->AssertNull($empty);
        $category = $this->categoryRepository->findOneRandom();
        if ($category instanceof Category) {
            $posts = $this->repository->findAllActiveByCategory($category);
            $this->assertTrue($posts instanceof Query);
        }
    }

    public function testfindAllActive()
    {
        $posts = $this->repository->findAllActive();
        $this->assertTrue($posts instanceof Query);
    }
}
