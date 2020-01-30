<?php

namespace Labstag\Repository;

use Doctrine\Common\Persistence\ManagerRegistry;
use Doctrine\ORM\QueryBuilder;
use Labstag\Entity\Tags;
use Labstag\Lib\ServiceEntityRepositoryLib;

/**
 * @method Tags|null find($id, $lockMode = null, $lockVersion = null)
 * @method Tags|null findOneBy(array $criteria, array $orderBy = null)
 * @method Tags[]    findAll()
 * @method Tags[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TagsRepository extends ServiceEntityRepositoryLib
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Tags::class);
    }

    public function findTagsByTypeNotTemporary(?string $type): ?QueryBuilder
    {
        if (is_null($type)) {
            return null;
        }

        $params = [
            'type'      => $type,
            'temporary' => true,
        ];

        $query = $this->createQueryBuilder('g');
        $query->where('g.type=:type AND g.temporary=:temporary');
        $query->setParameters($params);
        $query->orderBy('g.name', 'ASC');

        return $query;
    }

    public function findTagsByType(?string $type): ?QueryBuilder
    {
        if (is_null($type)) {
            return null;
        }

        $params = ['type' => $type];

        $query = $this->createQueryBuilder('g');
        $query->where('g.type=:type');
        $query->setParameters($params);
        $query->orderBy('g.name', 'ASC');

        return $query;
    }

    // /**
    //  * @return Tags[] Returns an array of Tags objects
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
    public function findOneBySomeField($value): ?Tags
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
