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
            $this->assertTrue($random instanceof Bookmark);
            return;
        }

        $this->assertTrue(true);
    }
}
