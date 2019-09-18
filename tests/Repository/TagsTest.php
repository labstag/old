<?php

namespace Labstag\Tests\Repository;

use Labstag\Entity\Tags;
use Labstag\Lib\RepositoryTestLib;
use Labstag\Repository\TagsRepository;

/**
 * @internal
 * @coversNothing
 */
class TagsTest extends RepositoryTestLib
{

    /**
     * @var TagsRepository
     */
    private $repository;

    public function setUp(): void
    {
        parent::setUp();
        $this->repository = $this->entityManager->getRepository(
            Tags::class
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
            $this->assertTrue($random instanceof Tags);
        }
    }

    public function testfindTagsByTypeNotTemporary()
    {
        $empty = $this->repository->findTagsByTypeNotTemporary(null);
        $tags  = $this->repository->findTagsByTypeNotTemporary('');
        $this->assertTrue(is_array($tags));
    }

    public function testfindTagsByType()
    {
        $empty = $this->repository->findTagsByType(null);
        $tags  = $this->repository->findTagsByType('');
        $this->assertTrue(is_array($tags));
    }
}
