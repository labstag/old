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

    public function testFindAll()
    {
        $all = $this->repository->findAll();
        $this->assertTrue(is_array($all));
    }

    public function testloginToken()
    {
        $empty = $this->repository->loginToken(null);
        $user = $this->repository->loginToken('');
    }

    public function testlogin()
    {
        $empty = $this->repository->login(null);
        $user = $this->repository->login('');
    }
}
