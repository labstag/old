<?php

namespace Labstag\Tests\Repository;

use Labstag\Entity\Template;
use Labstag\Lib\RepositoryTestLib;
use Labstag\Repository\TemplateRepository;

/**
 * @internal
 * @coversNothing
 */
class TemplateTest extends RepositoryTestLib
{

    /**
     * @var TemplateRepository
     */
    private $repository;

    public function setUp(): void
    {
        parent::setUp();
        /** @var TemplateRepository $repository */
        $repository       = $this->entityManager->getRepository(
            Template::class
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
            $this->assertSame(get_class($random), Template::class);

            return;
        }

        $this->assertTrue(true);
    }
}
