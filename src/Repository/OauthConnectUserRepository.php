<?php

namespace Labstag\Repository;

use Labstag\Entity\OauthConnectUser;
use Labstag\Entity\User;
use Labstag\Lib\ServiceEntityRepositoryLib;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method null|OauthConnectUser find($id, $lockMode = null, $lockVersion = null)
 * @method null|OauthConnectUser findOneBy(array $criteria, array $orderBy = null)
 * @method OauthConnectUser[]    findAll()
 * @method OauthConnectUser[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class OauthConnectUserRepository extends ServiceEntityRepositoryLib
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, OauthConnectUser::class);
    }

    public function findOauthNotUser(User $user, $identity, string $client)
    {
        $dql = $this->createQueryBuilder('p');
        $dql->where('p.refuser=:iduser');
        $dql->andWhere('p.identity=:identity');
        $dql->andWhere('p.name=:name');
        $dql->setParameters(
            [
                'iduser'   => $user->getId(),
                'name'     => $client,
                'identity' => $identity,
            ]
        );

        return $dql->getQuery()->getOneOrNullResult();
    }

    public function findOneOauthByUser(string $oauthCode, User $user)
    {
        $dql = $this->createQueryBuilder('p');
        $dql->where('p.name=:name');
        $dql->andWhere('p.refuser=:iduser');
        $dql->setParameters(
            [
                'iduser' => $user->getId(),
                'name'   => $oauthCode,
            ]
        );

        return $dql->getQuery()->getOneOrNullResult();
    }

    // /**
    //  * @return OauthConnectUser[] Returns an array of OauthConnectUser objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('o')
            ->andWhere('o.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('o.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?OauthConnectUser
    {
        return $this->createQueryBuilder('o')
            ->andWhere('o.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */

    public function login($identity, $oauth)
    {
        $builder = $this->createQueryBuilder('u');
        $builder->where(
            'u.name = :name AND u.identity = :identity'
        );
        $builder->setParameters(
            [
                'name'     => $oauth,
                'identity' => $identity,
            ]
        );

        return $builder->getQuery()->getOneOrNullResult();
    }

    public function findDistinctAllOauth()
    {
        $builder = $this->createQueryBuilder('u');
        $builder->select('u.name');
        $builder->distinct('u.name');
        $builder->orderBy('u.name', 'ASC');

        return $builder->getQuery()->getResult();
    }
}