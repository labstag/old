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

        $dql = $this->createQueryBuilder('h');
        $dql->innerJoin('h.refuser', 'u');
        $dql->where('h.enable = :enable');
        $dql->andWhere('b.createdAt<=now()');
        $dql->andWhere('u.id = :iduser');
        $dql->orderBy('h.createdAt', 'DESC');
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
        $dql = $this->createQueryBuilder('h');
        $dql->join('h.chapitres', 'c');
        $dql->where('h.enable = :enable');
        $dql->andWhere('h.createdAt<=now()');
        $dql->orderBy('h.updatedAt', 'DESC');
        $dql->setParameters(
            ['enable' => true]
        );

        return $dql->getQuery();
    }
}
