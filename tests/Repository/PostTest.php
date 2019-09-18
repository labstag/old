<?php

namespace Labstag\Tests\Repository;

use Labstag\Entity\Post;
use Labstag\Lib\RepositoryTestLib;
use Labstag\Repository\PostRepository;

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

    public function setUp(): void
    {
        parent::setUp();
        $this->repository = $this->entityManager->getRepository(
            Post::class
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
        $posts = $this->repository->findAllActiveByUser('');
        $this->assertTrue(is_array($posts));
    }

    public function testfindAllActiveByTag()
    {
        $empty = $this->repository->findAllActiveByTag(null);
        $posts = $this->repository->findAllActiveByTag('');
        $this->assertTrue(is_array($posts));
    }

    public function testfindAllActiveByCategory()
    {
        $empty = $this->repository->findAllActiveByCategory(null);
        $posts = $this->repository->findAllActiveByCategory('');
        $this->assertTrue(is_array($posts));
    }

    public function testfindAllActive()
    {
        $posts = $this->repository->findAllActive();
        $this->assertTrue(is_array($posts));
    }
}
