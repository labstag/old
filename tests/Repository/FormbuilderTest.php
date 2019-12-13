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
        $this->repository = $this->entityManager->getRepository(
            Formbuilder::class
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
            $this->assertTrue($random instanceof Formbuilder);
            return;
        }

        $this->assertTrue(true);
    }
}
