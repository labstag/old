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
        $this->repository = $this->entityManager->getRepository(
            Configuration::class
        );
    }

    public function testFindAll()
    {
        $all = $this->repository->findAll();
        $this->assertTrue(is_array($all));
    }

    public function testgetDataArray()
    {
        $entities = $this->repository->getDataArray();
        $this->assertTrue(is_array($entities));
    }
}
