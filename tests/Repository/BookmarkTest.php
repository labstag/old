<?php

namespace Labstag\Tests\Repository;

use Labstag\Entity\Bookmark;
use Labstag\Lib\RepositoryTestLib;
use Labstag\Repository\BookmarkRepository;

/**
 * @internal
 * @coversNothing
 */
class BookmarkTest extends RepositoryTestLib
{

    /**
     * @var BookmarkRepository
     */
    private $repository;

    public function setUp(): void
    {
        parent::setUp();
        $this->repository = $this->entityManager->getRepository(
            Bookmark::class
        );
    }
}
