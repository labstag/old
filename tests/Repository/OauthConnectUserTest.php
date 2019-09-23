<?php

namespace Labstag\Tests\Repository;

use Labstag\Entity\OauthConnectUser;
use Labstag\Entity\User;
use Labstag\Lib\RepositoryTestLib;
use Labstag\Repository\OauthConnectUserRepository;
use Labstag\Repository\UserRepository;

/**
 * @internal
 * @coversNothing
 */
class OauthConnectUserTest extends RepositoryTestLib
{

    /**
     * @var OauthConnectUserRepository
     */
    private $repository;

    /**
     * @var UserRepository
     */
    private $userRepository;

    public function setUp(): void
    {
        parent::setUp();
        $this->repository     = $this->entityManager->getRepository(
            OauthConnectUser::class
        );
        $this->userRepository = $this->entityManager->getRepository(
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
            $this->assertTrue($random instanceof OauthConnectUser);
        }
    }

    public function testfindOauthNotUser()
    {
        $empty = $this->repository->findOauthNotUser(null, null, null);
        $this->assertTrue(is_null($empty));
        $random = $this->repository->findOneRandom();
        $user   = $this->userRepository->findOneRandom();
        if ($random instanceof OauthConnectUser && $user instanceof User) {
            $oauth = $this->repository->findOauthNotUser(
                $user,
                $random->getIdentity(),
                $random->getName()
            );
        }
    }

    public function testfindOneOauthByUser()
    {
        $empty = $this->repository->findOneOauthByUser(null, null);
        $this->assertTrue(is_null($empty));
        $random = $this->repository->findOneRandom();
        $user   = $this->userRepository->findOneRandom();
        if ($user instanceof User && $random instanceof OauthConnectUser) {
            $user = $this->repository->findOneOauthByUser(
                $random->getName(),
                $user
            );
        }
    }

    public function testlogin()
    {
        $empty = $this->repository->login(null, null);
        $this->assertTrue(is_null($empty));
        $random = $this->repository->findOneRandom();
        if ($random instanceof OauthConnectUser) {
            $oauth = $this->repository->login(
                $random->getName(),
                $random->getIdentity()
            );
        }
    }

    public function testfindDistinctAllOauth()
    {
        $oauths = $this->repository->findDistinctAllOauth();
        $this->assertTrue(is_array($oauths));
    }
}
