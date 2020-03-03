<?php

namespace Labstag\Repository;

use Doctrine\Common\Persistence\ManagerRegistry;
use Doctrine\ORM\QueryBuilder;
use Labstag\Entity\Email;
use Labstag\Entity\User;
use Labstag\Lib\ServiceEntityRepositoryLib;

/**
 * @method Email|null find($id, $lockMode = null, $lockVersion = null)
 * @method Email|null findOneBy(array $criteria, array $orderBy = null)
 * @method Email[]    findAll()
 * @method Email[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class EmailRepository extends ServiceEntityRepositoryLib
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Email::class);
    }

    /**
     * @return QueryBuilder|void
     */
    public function findEmailByUser(?User $user)
    {
        if (is_null($user)) {
            return;
        }

        $params = [
            'refuser' => $user,
            'checked' => true,
        ];

        $dql = $this->createQueryBuilder('g');
        $dql->where('g.refuser = :refuser');
        $dql->andWhere('g.checked = :checked');
        $dql->setParameters($params);
        $dql->orderBy('g.adresse', 'ASC');

        return $dql;
    }
}
