<?php

namespace Labstag\Lib;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;

abstract class ServiceEntityRepositoryLib extends ServiceEntityRepository
{
    public function findDataInTrash()
    {
        $entityManager = $this->getEntityManager();
        $entityManager->getFilters()->disable('softdeleteable');
        $dql = $entityManager->createQueryBuilder();
        $dql->select('e');
        $dql->from($this->_entityName, 'e');
        $dql->where('e.deletedAt IS NOT NULL');
        $query = $dql->getQuery();

        return $query->getResult();
    }

    public function findOneDateInTrash($guid)
    {
        $entityManager = $this->getEntityManager();
        $entityManager->getFilters()->disable('softdeleteable');
        $dql = $entityManager->createQueryBuilder();
        $dql->select('e');
        $dql->from($this->_entityName, 'e');
        $dql->where('e.deletedAt IS NOT NULL AND e.id=:id');
        $dql->setParameter('id', $guid);
        $query = $dql->getQuery();

        return $query->getOneOrNullResult();
    }
}
