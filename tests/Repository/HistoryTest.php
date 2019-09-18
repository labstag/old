<?php

namespace Labstag\Tests\Repository;

use Labstag\Entity\History;
use Labstag\Lib\RepositoryTestLib;
use Labstag\Repository\HistoryRepository;

/**
 * @internal
 * @coversNothing
 */
class HistoryTest extends RepositoryTestLib
{

    /**
     * @var HistoryRepository
     */
    private $repository;

    public function setUp(): void
    {
        parent::setUp();
        $this->repository = $this->entityManager->getRepository(
            History::class
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
            $this->assertTrue($random instanceof History);
        }
    }

    public function testfindAllActiveByUser()
    {
        $empty     = $this->repository->findAllActiveByUser(null);
        $histories = $this->repository->findAllActiveByUser('');
        $this->assertTrue(is_array($histories));
    }

    public function testfindAllActive()
    {
        $histories = $this->repository->findAllActive();
        $this->assertTrue(is_array($histories));
    }
}
