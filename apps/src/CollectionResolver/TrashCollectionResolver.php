<?php

namespace Labstag\CollectionResolver;

use Symfony\Component\DependencyInjection\ContainerInterface;
use ApiPlatform\Core\GraphQl\Resolver\QueryCollectionResolverInterface;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;

final class TrashCollectionResolver implements QueryCollectionResolverInterface
{

    /**
     * @var EntityManager | EntityManagerInterface
     */
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }


    /**
     * @param iterable $collection
     *
     * @return iterable
     */
    public function __invoke(iterable $collection, array $context): iterable
    {
        unset($content);
        $filters = $this->entityManager->getFilters();
        $filters->disable('softdeleteable');
        $filters->enable('labstag_trash');

        return $collection;
    }
}
