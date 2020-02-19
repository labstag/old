<?php

namespace Labstag\Repository;

use Doctrine\Common\Persistence\ManagerRegistry;
use Doctrine\ORM\QueryBuilder;
use Labstag\Entity\Tag;
use Labstag\Lib\ServiceEntityRepositoryLib;

/**
 * @method Tag|null find($id, $lockMode = null, $lockVersion = null)
 * @method Tag|null findOneBy(array $criteria, array $orderBy = null)
 * @method Tag[]    findAll()
 * @method Tag[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TagRepository extends ServiceEntityRepositoryLib
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Tag::class);
    }

    /**
     * @return QueryBuilder|void
     */
    public function findTagByTypeNotTemporary(?string $type)
    {
        if (is_null($type)) {
            return;
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

    /**
     * @return QueryBuilder|void
     */
    public function findTagByType(?string $type)
    {
        if (is_null($type)) {
            return;
        }

        $params = ['type' => $type];

        $query = $this->createQueryBuilder('g');
        $query->where('g.type=:type');
        $query->setParameters($params);
        $query->orderBy('g.name', 'ASC');

        return $query;
    }

    // /**
    //  * @return Tag[] Returns an array of Tag objects
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
    public function findOneBySomeField($value): ?Tag
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
