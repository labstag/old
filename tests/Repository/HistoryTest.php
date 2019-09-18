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
}
