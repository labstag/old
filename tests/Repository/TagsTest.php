<?php

namespace Labstag\Tests\Repository;

use Labstag\Entity\Tags;
use Labstag\Lib\RepositoryTestLib;
use Labstag\Repository\TagsRepository;

/**
 * @internal
 * @coversNothing
 */
class TagsTest extends RepositoryTestLib
{

    /**
     * @var TagsRepository
     */
    private $repository;

    public function setUp(): void
    {
        parent::setUp();
        $this->repository = $this->entityManager->getRepository(
            Tags::class
        );
    }

    public function findTagsByTypeNotTemporary()
    {
        $empty = $this->repository->findTagsByTypeNotTemporary(null);
        $tags = $this->repository->findTagsByTypeNotTemporary('');
    }

    public function findTagsByType()
    {
        $empty = $this->repository->findTagsByType(null);
        $tags = $this->repository->findTagsByType('');
    }
}
