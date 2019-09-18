<?php

namespace Labstag\Tests\Repository;

use Labstag\Lib\RepositoryTestLib;
use Labstag\Repository\FormbuilderRepository;
use Symfony\Component\Form\FormBuilder;

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
            FormBuilder::class
        );
    }
}
