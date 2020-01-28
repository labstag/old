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
        /** @var HistoryRepository $repository */
        $repository     = $this->entityManager->getRepository(
            History::class
        );
        $this->repository = $repository;
        /** @var UserRepository $userRepository */
        $userRepository = $this->entityManager->getRepository(
            User::class
        );
        $this->userRepository = $userRepository;
    }

    public function testFindAll(): void
    {
        $all = $this->repository->findAll();
        $this->assertTrue(is_array($all));
    }

    public function testfindOneRandom(): void
    {
        $all = $this->repository->findAll();
        if (0 != count($all)) {
            $random = $this->repository->findOneRandom();
            $this->assertSame(get_class($random), History::class);

            return;
        }

        $this->assertTrue(true);
    }

    public function testfindAllActiveByUser(): void
    {
        $empty = $this->repository->findAllActiveByUser(null);
        $this->AssertNull($empty);
        $user = $this->userRepository->findOneRandom();
        if ($user instanceof User) {
            /** @var Query $histories */
            $histories = $this->repository->findAllActiveByUser($user);
            $this->assertSame(get_class($histories), Query::class);

            return;
        }

        $this->assertTrue(true);
    }

    public function testfindAllActive(): void
    {
        $histories = $this->repository->findAllActive();
        $this->assertSame(get_class($histories), Query::class);
    }
}
