<?php

namespace Labstag\Tests\Repository;

use Labstag\Entity\Templates;
use Labstag\Lib\RepositoryTestLib;
use Labstag\Repository\TemplatesRepository;

/**
 * @internal
 * @coversNothing
 */
class TemplatesTest extends RepositoryTestLib
{

    /**
     * @var TemplatesRepository
     */
    private $repository;

    public function setUp(): void
    {
        parent::setUp();
        /** @var TemplatesRepository $repository */
        $repository       = $this->entityManager->getRepository(
            Templates::class
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
            $this->assertSame(get_class($random), Templates::class);

            return;
        }

        $this->assertTrue(true);
    }
}
