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
     * @param iterable<> $collection
     *
     * @return iterable<>
     */
    public function __invoke(iterable $collection, array $context): iterable
    {
        $this->entityManager->getFilters()->disable('softdeleteable');
        // Query arguments are in $context['args'].

        foreach ($collection as $iter => $category) {
            // Do something with the book.
        }

        return $collection;
    }
}
