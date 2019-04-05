<?php

namespace Labstag\Repository;

use Labstag\Entity\OauthConnectUser;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method OauthConnectUser|null find($id, $lockMode = null, $lockVersion = null)
 * @method OauthConnectUser|null findOneBy(array $criteria, array $orderBy = null)
 * @method OauthConnectUser[]    findAll()
 * @method OauthConnectUser[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class OauthConnectUserRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, OauthConnectUser::class);
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
}
