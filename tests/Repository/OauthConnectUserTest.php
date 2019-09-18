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
        $empty = $this->repository->findOauthNotUser(null, '', '');
        $oauth = $this->repository->findOauthNotUser('', '', '');
    }

    public function testfindOneOauthByUser()
    {
        $empty = $this->repository->findOneOauthByUser('', null);
        $user = $this->repository->findOneOauthByUser('', '');
    }

    public function testlogin()
    {
        $empty = $this->repository->login(null, null);
        $oauth = $this->repository->login('','');
    }

    public function testfindDistinctAllOauth()
    {
        $oauths = $this->repository->findDistinctAllOauth();
        $this->assertTrue(is_array($oauths));
    }
}
