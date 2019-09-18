<?php

namespace Labstag\Tests\Repository;

use Labstag\Entity\Chapitre;
use Labstag\Lib\RepositoryTestLib;
use Labstag\Repository\ChapitreRepository;

/**
 * @internal
 * @coversNothing
 */
class ChapitreTest extends RepositoryTestLib
{

    /**
     * @var ChapitreRepository
     */
    private $repository;

    public function setUp(): void
    {
        parent::setUp();
        $this->repository = $this->entityManager->getRepository(
            Chapitre::class
        );
    }

    public function testFindAll()
    {
        $all = $this->repository->findAll();
        $this->assertTrue(is_array($all));
    }
}
