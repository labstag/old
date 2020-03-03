<?php

namespace Labstag\Repository;

use Doctrine\Common\Persistence\ManagerRegistry;
use Doctrine\ORM\Query;
use Labstag\Entity\Category;
use Labstag\Entity\Post;
use Labstag\Entity\Tag;
use Labstag\Entity\User;
use Labstag\Lib\ServiceEntityRepositoryLib;

/**
 * @method Post|null find($id, $lockMode = null, $lockVersion = null)
 * @method Post|null findOneBy(array $criteria, array $orderBy = null)
 * @method Post[]    findAll()
 * @method Post[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PostRepository extends ServiceEntityRepositoryLib
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Post::class);
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
        $dql->where('p.enable=:enable');
        $dql->andWhere('u.id=:iduser');
        $dql->andWhere('p.createdAt<=now()');
        $dql->orderBy('p.createdAt', 'DESC');
        $dql->setParameters(
            [
                'iduser' => $user->getId(),
                'enable' => true,
            ]
        );

        return $dql->getQuery();
    }

    /**
     * @return Query|void
     */
    public function findAllActiveByTag(?Tag $tag)
    {
        if (is_null($tag)) {
            return;
        }

        $dql = $this->createQueryBuilder('p');
        $dql->innerJoin('p.tags', 't');
        $dql->where('p.enable=:enable');
        $dql->andWhere('t.id=:idtag');
        $dql->andWhere('p.createdAt<=now()');
        $dql->orderBy('p.createdAt', 'DESC');
        $dql->setParameters(
            [
                'idtag'  => $tag->getId(),
                'enable' => true,
            ]
        );

        return $dql->getQuery();
    }

    /**
     * @return Query|void
     */
    public function findAllActiveByCategory(?Category $category)
    {
        if (is_null($category)) {
            return;
        }

        $dql = $this->createQueryBuilder('p');
        $dql->innerJoin('p.refcategory', 'c');
        $dql->where('p.enable=:enable');
        $dql->andWhere('c.id=:idcategory');
        $dql->andWhere('p.createdAt<=now()');
        $dql->orderBy('p.createdAt', 'DESC');
        $dql->setParameters(
            [
                'idcategory' => $category->getId(),
                'enable'     => true,
            ]
        );

        return $dql->getQuery();
    }

    public function findAllActive(): Query
    {
        $dql = $this->createQueryBuilder('p');
        $dql->where('p.enable=:enable AND p.createdAt<=now()');
        $dql->orderBy('p.createdAt', 'DESC');
        $dql->setParameters(
            ['enable' => true]
        );

        return $dql->getQuery();
    }

    // /**
    //  * @return Post[] Returns an array of Post objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('p.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Post
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
