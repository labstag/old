<?php

namespace Labstag\Tests\Repository;

use Labstag\Entity\User;
use Labstag\Lib\RepositoryTestLib;
use Labstag\Repository\UserRepository;

/**
 * @internal
 * @coversNothing
 */
class UserTest extends RepositoryTestLib
{

    /**
     * @var UserRepository
     */
    private $repository;

    public function setUp(): void
    {
        parent::setUp();
        $this->repository = $this->entityManager->getRepository(
            User::class
        );
    }
}
