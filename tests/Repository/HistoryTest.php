<?php

namespace Labstag\Tests\Repository;

use Doctrine\ORM\Query;
use Labstag\Entity\History;
use Labstag\Entity\User;
use Labstag\Lib\RepositoryTestLib;
use Labstag\Repository\HistoryRepository;
use Labstag\Repository\UserRepository;

/**
 * @internal
 * @coversNothing
 */
class HistoryTest extends RepositoryTestLib
{

    /**
     * @var HistoryRepository
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
            History::class
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
            $this->assertSame(get_class($random), History::class);

            return;
        }

        $this->assertTrue(true);
    }

    public function testfindAllActiveByUser()
    {
        $empty = $this->repository->findAllActiveByUser(null);
        $this->AssertNull($empty);
        $user = $this->userRepository->findOneRandom();
        if ($user instanceof User) {
            $histories = $this->repository->findAllActiveByUser($user);
            $this->assertSame(get_class($histories), Query::class);
        }
    }

    public function testfindAllActive()
    {
        $histories = $this->repository->findAllActive();
        $this->assertSame(get_class($histories), Query::class);
    }
}
