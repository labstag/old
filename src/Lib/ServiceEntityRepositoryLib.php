<?php

namespace Labstag\Lib;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;

abstract class ServiceEntityRepositoryLib extends ServiceEntityRepository
{
    /**
     * @return mixed
     */
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

    /**
     * @return mixed|null
     */
    public function findOneDateInTrash(?string $guid)
    {
        if (is_null($guid)) {
            return null;
        }

        $entityManager = $this->getEntityManager();
        $entityManager->getFilters()->disable('softdeleteable');
        $dql = $entityManager->createQueryBuilder();
        $dql->select('e');
        $dql->from($this->_entityName, 'e');
        $dql->where('e.deletedAt IS NOT NULL AND e.id=:id');
        $dql->setParameter('id', $guid);

        return $dql->getQuery()->getOneOrNullResult();
    }

    /**
     * @return mixed|null
     */
    public function findOneRandom(string $where = '', array $params = [])
    {
        $entityManager = $this->getEntityManager();
        $dql           = $entityManager->createQueryBuilder();
        $dql->select('e');
        $dql->addSelect('RAND() as HIDDEN rand');
        $dql->from($this->_entityName, 'e');
        if ('' != $where) {
            $dql->where($where);
            $dql->setParameters($params);
        }

        $dql->orderBy('rand');
        $dql->setMaxResults(1);

        return $dql->getQuery()->getOneOrNullResult();
    }

    public function getDataArray(): array
    {
        return $this->createQueryBuilder('c')->getQuery()->getScalarResult();
    }
}
