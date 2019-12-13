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
        $this->repository = $this->entityManager->getRepository(
            Templates::class
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
            $this->assertTrue($random instanceof Templates);
            return;
        }

        $this->assertTrue(true);
    }
}
