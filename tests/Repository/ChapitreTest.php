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
            $this->assertSame(get_class($random), Chapitre::class);

            return;
        }

        $this->assertTrue(true);
    }
}
