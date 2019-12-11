<?php

namespace Labstag\Repository;

use Doctrine\ORM\QueryBuilder;
use Labstag\Entity\Category;
use Labstag\Lib\ServiceEntityRepositoryLib;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method null|Category find($id, $lockMode = null, $lockVersion = null)
 * @method null|Category findOneBy(array $criteria, array $orderBy = null)
 * @method Category[]    findAll()
 * @method Category[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CategoryRepository extends ServiceEntityRepositoryLib
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Category::class);
    }

    public function findForForm(): QueryBuilder
    {
        return $this->createQueryBuilder('g')->orderBy('g.name', 'ASC');
    }

    // /**
    //  * @return Category[] Returns an array of Category objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('c.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Category
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
