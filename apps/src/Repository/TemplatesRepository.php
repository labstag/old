<?php

namespace Labstag\Repository;

use Doctrine\Common\Persistence\ManagerRegistry;
use Labstag\Entity\Templates;
use Labstag\Lib\ServiceEntityRepositoryLib;

/**
 * @method Templates|null find($id, $lockMode = null, $lockVersion = null)
 * @method Templates|null findOneBy(array $criteria, array $orderBy = null)
 * @method Templates[]    findAll()
 * @method Templates[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TemplatesRepository extends ServiceEntityRepositoryLib
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Templates::class);
    }

    // /**
    //  * @return Templates[] Returns an array of Templates objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('t.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Templates
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
