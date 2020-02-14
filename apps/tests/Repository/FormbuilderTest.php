<?php

namespace Labstag\Tests\Repository;

use Labstag\Entity\Formbuilder;
use Labstag\Lib\RepositoryTestLib;
use Labstag\Repository\FormbuilderRepository;

/**
 * @internal
 * @coversNothing
 */
class FormbuilderTest extends RepositoryTestLib
{

    /**
     * @var FormbuilderRepository
     */
    private $repository;

    public function setUp(): void
    {
        parent::setUp();
        /** @var FormbuilderRepository $repository */
        $repository       = $this->entityManager->getRepository(
            Formbuilder::class
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
            $this->assertSame(get_class($random), Formbuilder::class);

            return;
        }

        $this->assertTrue(true);
    }
}
