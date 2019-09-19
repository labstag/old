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

    public function testfindOneRandom()
    {
        $all = $this->repository->findAll();
        if (0 != count($all)) {
            $random = $this->repository->findOneRandom();
            $this->assertTrue($random instanceof User);
        }
    }

    public function testloginToken()
    {
        $empty = $this->repository->loginToken(null);
        $this->assertTrue(is_null($empty));
        $user = $this->repository->findOneRandom();
        if ($user instanceof User) {
            $user = $this->repository->loginToken($user->getApiKey());
            $this->assertTrue($user instanceof User || is_null($user));
        }
    }

    public function testlogin()
    {
        $empty = $this->repository->login(null);
        $this->assertTrue(is_null($empty));
        $user = $this->repository->findOneRandom();
        if ($user instanceof User) {
            $user = $this->repository->login(
                $user->getUsername()
            );
            $this->assertTrue($user instanceof User);
            $user = $this->repository->login(
                $user->getEmail()
            );
            $this->assertTrue($user instanceof User);
        }
    }
}
