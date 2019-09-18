<?php

namespace Labstag\Tests\Repository;

use Labstag\Entity\Email;
use Labstag\Entity\User;
use Labstag\Lib\RepositoryTestLib;
use Labstag\Repository\EmailRepository;

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

    public function setUp(): void
    {
        parent::setUp();
        $this->repository = $this->entityManager->getRepository(
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

        $user = $this->repository->findEmailByUser('');
    }
}
