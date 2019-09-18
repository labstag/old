<?php

namespace Labstag\Tests\Repository;

use Labstag\Entity\OauthConnectUser;
use Labstag\Lib\RepositoryTestLib;
use Labstag\Repository\OauthConnectUserRepository;

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

    public function setUp(): void
    {
        parent::setUp();
        $this->repository = $this->entityManager->getRepository(
            OauthConnectUser::class
        );
    }

    public function findOauthNotUser()
    {
        $empty = $this->repository->findOauthNotUser(null, '', '');
        $oauth = $this->repository->findOauthNotUser('', '', '');
    }

    public function findOneOauthByUser()
    {
        $empty = $this->repository->findOneOauthByUser('', null);
        $user = $this->repository->findOneOauthByUser('', '');
    }

    public function login()
    {
        $empty = $this->repository->login(null, null);
        $oauth = $this->repository->login('','');
    }

    public function findDistinctAllOauth()
    {
        $oauths = $this->repository->findDistinctAllOauth();
    }
}
