<?php

namespace Labstag\Repository;

use Labstag\Entity\Edito;
use Labstag\Lib\ServiceEntityRepositoryLib;
use Doctrine\Common\Persistence\ManagerRegistry;
use Doctrine\ORM\QueryBuilder;

/**
 * @method Edito|null find($id, $lockMode = null, $lockVersion = null)
 * @method Edito|null findOneBy(array $criteria, array $orderBy = null)
 * @method Edito[]    findAll()
 * @method Edito[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class EditoRepository extends ServiceEntityRepositoryLib
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Edito::class);
    }

    public function findAllActive(): QueryBuilder
    {
        $dql = $this->createQueryBuilder('b');
        $dql->where('b.createdAt<=now()');
        $dql->orderBy('b.createdAt', 'DESC');

        return $dql;
    }
}
