<?php

namespace Labstag\Tests\Repository;

use Labstag\Entity\Category;
use Labstag\Lib\RepositoryTestLib;
use Labstag\Repository\CategoryRepository;

/**
 * @internal
 * @coversNothing
 */
class CategoryTest extends RepositoryTestLib
{

    /**
     * @var CategoryRepository
     */
    private $repository;

    public function setUp(): void
    {
        parent::setUp();
        $this->repository = $this->entityManager->getRepository(
            Category::class
        );
    }
    
    public function findForForm()
    {
        $entities = $this->repository->findForForm();
    }
}
