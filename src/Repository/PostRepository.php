<?php

namespace Labstag\Repository;

use Labstag\Entity\Category;
use Labstag\Entity\Post;
use Labstag\Entity\Tags;
use Labstag\Entity\User;
use Labstag\Lib\ServiceEntityRepositoryLib;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method null|Post find($id, $lockMode = null, $lockVersion = null)
 * @method null|Post findOneBy(array $criteria, array $orderBy = null)
 * @method Post[]    findAll()
 * @method Post[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PostRepository extends ServiceEntityRepositoryLib
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Post::class);
    }

    public function findAllActiveByUser(User $user)
    {
        $dql = $this->createQueryBuilder('p');
        $dql->innerJoin('p.refuser', 'u');
        $dql->where('p.enable=:enable');
        $dql->andWhere('u.id=:iduser');
        $dql->orderBy('p.createdAt', 'DESC');
        $dql->setParameters(
            [
                'iduser' => $user->getId(),
                'enable' => true,
            ]
        );

        return $dql->getQuery();
    }

    public function findAllActiveByTag(Tags $tag)
    {
        $dql = $this->createQueryBuilder('p');
        $dql->innerJoin('p.tags', 't');
        $dql->where('p.enable=:enable');
        $dql->andWhere('t.id=:idtag');
        $dql->orderBy('p.createdAt', 'DESC');
        $dql->setParameters(
            [
                'idtag'  => $tag->getId(),
                'enable' => true,
            ]
        );

        return $dql->getQuery();
    }

    public function findAllActiveByCategory(Category $category)
    {
        $dql = $this->createQueryBuilder('p');
        $dql->innerJoin('p.refcategory', 'c');
        $dql->where('p.enable=:enable');
        $dql->andWhere('c.id=:idcategory');
        $dql->orderBy('p.createdAt', 'DESC');
        $dql->setParameters(
            [
                'idcategory' => $category->getId(),
                'enable'     => true,
            ]
        );

        return $dql->getQuery()->getResult();
    }

    public function findAllActive()
    {
        $dql = $this->createQueryBuilder('p');
        $dql->where('p.enable=:enable');
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
