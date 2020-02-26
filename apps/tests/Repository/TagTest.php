<?php

namespace Labstag\Tests\Repository;

use Doctrine\ORM\QueryBuilder;
use Labstag\Entity\Tag;
use Labstag\Lib\RepositoryTestLib;
use Labstag\Repository\TagRepository;

/**
 * @internal
 * @coversNothing
 */
class TagTest extends RepositoryTestLib
{

    /**
     * @var TagRepository
     */
    private $repository;

    public function setUp(): void
    {
        parent::setUp();
        /** @var TagRepository $repository */
        $repository       = $this->entityManager->getRepository(
            Tag::class
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
            $this->assertSame(get_class($random), Tag::class);

            return;
        }

        $this->assertTrue(true);
    }

    public function testfindTagByTypeNotTemporary(): void
    {
        /** @var null $empty */
        $empty = $this->repository->findTagByTypeNotTemporary(null);
        $this->AssertNull($empty);
        /** @var QueryBuilder $tags */
        $tags = $this->repository->findTagByTypeNotTemporary('');
        $this->assertSame(get_class($tags), QueryBuilder::class);
        $random = $this->repository->findOneRandom();
        /** @var QueryBuilder $tags */
        $tags = $this->repository->findTagByTypeNotTemporary(
            $random->getType()
        );
        $this->assertSame(get_class($tags), QueryBuilder::class);
    }

    public function testfindTagByType(): void
    {
        /** @var null $empty */
        $empty = $this->repository->findTagByType(null);
        $this->AssertNull($empty);
        /** @var QueryBuilder $tags */
        $tags = $this->repository->findTagByType('');
        $this->assertSame(get_class($tags), QueryBuilder::class);
        $random = $this->repository->findOneRandom();
        /** @var QueryBuilder $tags */
        $tags = $this->repository->findTagByType($random->getType());
        $this->assertSame(get_class($tags), QueryBuilder::class);
    }
}
