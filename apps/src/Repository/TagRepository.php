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

        $dql = $this->createQueryBuilder('g');
        $dql->where('g.type = :type');
        $dql->andWhere('g.temporary = :temporary');
        $dql->setParameters($params);
        $dql->orderBy('g.name', 'ASC');

        return $dql;
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

        $dql = $this->createQueryBuilder('g');
        $dql->where('g.type = :type');
        $dql->setParameters($params);
        $dql->orderBy('g.name', 'ASC');

        return $dql;
    }
}
