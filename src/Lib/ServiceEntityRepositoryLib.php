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

        return $dql->getQuery()->getResult();
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

        return $dql->getQuery()->getOneOrNullResult();
    }

    public function findOneRandom()
    {
        $entityManager = $this->getEntityManager();
        $dql = $entityManager->createQueryBuilder();
        $dql->select('e');
		$dql->addSelect('RAND() as HIDDEN rand');
        $dql->from($this->_entityName, 'e');
		$dql->orderBy('rand');
		$dql->setMaxResults(1);
		
		
        return $dql->getQuery()->getOneOrNullResult();
    }
}
