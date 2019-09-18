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
}
