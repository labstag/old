<?php

namespace Labstag\Resolver\Query\History;

use ApiPlatform\Core\GraphQl\Resolver\QueryCollectionResolverInterface;
use Labstag\Repository\HistoryRepository;

final class CollectionEnableResolver implements QueryCollectionResolverInterface
{
    public function __construct(HistoryRepository $repository)
    {
        $this->repository = $repository;
    }

    public function __invoke(iterable $collection, array $context): iterable
    {
        $query      = $this->repository->findAllActive();
        $dql        = $query->getDQL();
        $parameters = $query->getParameters();
        unset($context);
        $query = $collection->getQuery();
        $query->setDQL($dql);
        $query->setParameters($parameters);

        return $collection;
    }
}
