<?php

namespace Labstag\Repository;

use Doctrine\Common\Persistence\ManagerRegistry;
use Labstag\Entity\User;
use Labstag\Lib\ServiceEntityRepositoryLib;

/**
 * @method User|null find($id, $lockMode = null, $lockVersion = null)
 * @method User|null findOneBy(array $criteria, array $orderBy = null)
 * @method User[]    findAll()
 * @method User[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserRepository extends ServiceEntityRepositoryLib
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, User::class);
    }

    /**
     * @return User|void
     */
    public function loginToken(?string $token)
    {
        if (is_null($token)) {
            return;
        }

        $dql = $this->createQueryBuilder('u');
        $dql->where('u.enable = :enable');
        $dql->andWhere('u.apiKey = :apiKey');
        $dql->setParameters(
            [
                'enable' => true,
                'apiKey' => $token,
            ]
        );

        return $dql->getQuery()->getOneOrNullResult();
    }

    /**
     * @return User|void
     */
    public function login(?string $login)
    {
        if (is_null($login)) {
            return;
        }

        $dql = $this->createQueryBuilder('u');
        $dql->where('u.username = :username OR u.email = :email');
        $dql->setParameters(
            [
                'username' => $login,
                'email'    => $login,
            ]
        );

        return $dql->getQuery()->getOneOrNullResult();
    }
}
