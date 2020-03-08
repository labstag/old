<?php

namespace Labstag\Repository;

use Doctrine\Common\Persistence\ManagerRegistry;
use Doctrine\ORM\QueryBuilder;
use Labstag\Entity\Chapitre;
use Labstag\Lib\ServiceEntityRepositoryLib;

/**
 * @method Chapitre|null find($id, $lockMode = null, $lockVersion = null)
 * @method Chapitre|null findOneBy(array $criteria, array $orderBy = null)
 * @method Chapitre[]    findAll()
 * @method Chapitre[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ChapitreRepository extends ServiceEntityRepositoryLib
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Chapitre::class);
    }

    public function findAllActive(array $context = array()): QueryBuilder
    {
        $dql = $this->createQueryBuilder('c');
        $dql->where('c.createdAt<=now()');
        $this->setArgs('c', Chapitre::class, $context, $dql);
        $dql->orderBy('c.createdAt', 'DESC');

        return $dql;
    }
}
