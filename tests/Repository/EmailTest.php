<?php

namespace Labstag\Tests\Repository;

use Labstag\Entity\Email;
use Doctrine\ORM\QueryBuilder;
use Labstag\Entity\User;
use Labstag\Lib\RepositoryTestLib;
use Labstag\Repository\EmailRepository;
use Labstag\Repository\UserRepository;

/**
 * @internal
 * @coversNothing
 */
class EmailTest extends RepositoryTestLib
{

    /**
     * @var EmailRepository
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
            Email::class
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
            $this->assertTrue($random instanceof Email);
        }
    }

    public function testfindEmailByUser()
    {
        $empty = $this->repository->findEmailByUser(null);
        $this->assertTrue(is_null($empty));
        $user  = $this->userRepository->findOneRandom();
        if ($user instanceof User) {
            $emails = $this->repository->findEmailByUser($user);
            $this->assertTrue($emails instanceof QueryBuilder);
        }
    }
}
