<?php

namespace Labstag\Repository;

use Doctrine\Common\Persistence\ManagerRegistry;
use Doctrine\ORM\Query;
use Labstag\Entity\History;
use Labstag\Entity\User;
use Labstag\Lib\ServiceEntityRepositoryLib;

/**
 * @method History|null find($id, $lockMode = null, $lockVersion = null)
 * @method History|null findOneBy(array $criteria, array $orderBy = null)
 * @method History[]    findAll()
 * @method History[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class HistoryRepository extends ServiceEntityRepositoryLib
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, History::class);
    }

    /**
     * @return Query|void
     */
    public function findAllActiveByUser(?User $user)
    {
        if (is_null($user)) {
            return;
        }

        $dql = $this->createQueryBuilder('p');
        $dql->innerJoin('p.refuser', 'u');
        $dql->where('p.enable = :enable');
        $dql->andWhere('b.createdAt<=now()');
        $dql->andWhere('u.id = :iduser');
        $dql->orderBy('p.createdAt', 'DESC');
        $dql->setParameters(
            [
                'iduser' => $user->getId(),
                'enable' => true,
            ]
        );

        return $dql->getQuery();
    }

    public function findAllActive(): Query
    {
        $dql = $this->createQueryBuilder('p');
        $dql->join('p.chapitres', 'c');
        $dql->where('p.enable = :enable');
        $dql->andWhere('b.createdAt<=now()');
        $dql->orderBy('p.updatedAt', 'DESC');
        $dql->setParameters(
            ['enable' => true]
        );

        return $dql->getQuery();
    }
}
