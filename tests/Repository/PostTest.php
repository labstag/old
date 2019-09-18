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

    public function findAllActiveByUser()
    {
        $empty = $this->repository->findAllActiveByUser(null);
        $posts = $this->repository->findAllActiveByUser('');
    }

    public function findAllActiveByTag()
    {
        $empty = $this->repository->findAllActiveByTag(null);
        $posts = $this->repository->findAllActiveByTag('');
    }

    public function findAllActiveByCategory()
    {
        $empty = $this->repository->findAllActiveByCategory(null);
        $posts = $this->repository->findAllActiveByCategory('');
    }

    public function findAllActive()
    {
        $posts = $this->repository->findAllActive();
    }
}
