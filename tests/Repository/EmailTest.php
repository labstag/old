<?php

namespace Labstag\Tests\Repository;

use Labstag\Entity\Email;
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
    }

    public function findEmailByUser()
    {
        $empty = $this->repository->findEmailByUser(null);

        $user = $this->repository->findEmailByUser('');
    }
}
