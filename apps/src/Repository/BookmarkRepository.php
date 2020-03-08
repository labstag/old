<?php

namespace Labstag\Repository;

use Doctrine\Common\Persistence\ManagerRegistry;
use Doctrine\ORM\Query;
use Doctrine\ORM\QueryBuilder;
use Labstag\Entity\Bookmark;
use Labstag\Lib\ServiceEntityRepositoryLib;

/**
 * @method Bookmark|null find($id, $lockMode = null, $lockVersion = null)
 * @method Bookmark|null findOneBy(array $criteria, array $orderBy = null)
 * @method Bookmark[]    findAll()
 * @method Bookmark[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class BookmarkRepository extends ServiceEntityRepositoryLib
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Bookmark::class);
    }

    public function findAllActive(array $context = array()): QueryBuilder
    {
        $dql = $this->createQueryBuilder('b');
        $dql->where('b.createdAt<=now()');
        $this->setArgs('b', Bookmark::class, $context, $dql);
        $dql->orderBy('b.createdAt', 'DESC');

        return $dql;
    }
}
