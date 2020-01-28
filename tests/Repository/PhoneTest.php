<?php

namespace Labstag\Tests\Repository;

use Labstag\Entity\Phone;
use Labstag\Lib\RepositoryTestLib;
use Labstag\Repository\PhoneRepository;

/**
 * @internal
 * @coversNothing
 */
class PhoneTest extends RepositoryTestLib
{

    /**
     * @var PhoneRepository
     */
    private $repository;

    public function setUp(): void
    {
        parent::setUp();
        $this->repository = $this->entityManager->getRepository(
            Phone::class
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
            $this->assertSame(get_class($random), Phone::class);

            return;
        }

        $this->assertTrue(true);
    }
}
