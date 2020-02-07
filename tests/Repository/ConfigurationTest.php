<?php

namespace Labstag\Tests\Repository;

use Labstag\Entity\Configuration;
use Labstag\Lib\RepositoryTestLib;
use Labstag\Repository\ConfigurationRepository;

/**
 * @internal
 * @coversNothing
 */
class ConfigurationTest extends RepositoryTestLib
{

    /**
     * @var ConfigurationRepository
     */
    private $repository;

    public function setUp(): void
    {
        parent::setUp();
        /** @var ConfigurationRepository $repository */
        $repository       = $this->entityManager->getRepository(
            Configuration::class
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
            $this->assertSame(get_class($random), Configuration::class);

            return;
        }

        $this->assertTrue(true);
    }

    public function testgetDataArray(): void
    {
        $entities = $this->repository->getDataArray();
        $this->assertTrue(is_array($entities));
    }
}
