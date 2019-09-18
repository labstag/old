<?php

namespace Labstag\Repository;

use Doctrine\ORM\QueryBuilder;
use Labstag\Entity\Tags;
use Labstag\Lib\ServiceEntityRepositoryLib;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method null|Tags find($id, $lockMode = null, $lockVersion = null)
 * @method null|Tags findOneBy(array $criteria, array $orderBy = null)
 * @method Tags[]    findAll()
 * @method Tags[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TagsRepository extends ServiceEntityRepositoryLib
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Tags::class);
    }

    public function findTagsByTypeNotTemporary(string $type): QueryBuilder
    {
        $params = [
            'type'      => $type,
            'temporary' => true,
        ];

        return $this->createQueryBuilder('g')->where('g.type=:type AND g.temporary=:temporary')->setParameters($params)->orderBy('g.name', 'ASC');
    }

    public function findTagsByType(string $type): QueryBuilder
    {
        $params = ['type' => $type];

        return $this->createQueryBuilder('g')->where('g.type=:type')->setParameters($params)->orderBy('g.name', 'ASC');
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
