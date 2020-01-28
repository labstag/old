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
        /** @var BookmarkRepository $repository */
        $repository = $this->entityManager->getRepository(
            Bookmark::class
        );
        $this->repository = $repository;
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
            $this->assertSame(get_class($random), Bookmark::class);

            return;
        }

        $this->assertTrue(true);
    }
}
