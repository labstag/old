<?php

namespace Labstag\Tests\Repository;

use Doctrine\ORM\QueryBuilder;
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

            return;
        }

        $this->assertTrue(true);
    }

    public function testfindTagsByTypeNotTemporary()
    {
        $empty = $this->repository->findTagsByTypeNotTemporary(null);
        $this->assertTrue(is_null($empty));
        $tags = $this->repository->findTagsByTypeNotTemporary('');
        $this->assertTrue($tags instanceof QueryBuilder);
        $random = $this->repository->findOneRandom();
        $tags   = $this->repository->findTagsByTypeNotTemporary(
            $random->getType()
        );
        $this->assertTrue($tags instanceof QueryBuilder);
    }

    public function testfindTagsByType()
    {
        $empty = $this->repository->findTagsByType(null);
        $this->assertTrue(is_null($empty));
        $tags = $this->repository->findTagsByType('');
        $this->assertTrue($tags instanceof QueryBuilder);
        $random = $this->repository->findOneRandom();
        $tags   = $this->repository->findTagsByType($random->getType());
        $this->assertTrue($tags instanceof QueryBuilder);
    }
}
