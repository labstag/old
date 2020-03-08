<?php

namespace Labstag\Resolver\Query\Configuration;

use ApiPlatform\Core\GraphQl\Resolver\QueryCollectionResolverInterface;
use Labstag\Repository\ConfigurationRepository;

final class CollectionResolver implements QueryCollectionResolverInterface
{
    public function __construct(ConfigurationRepository $repository)
    {
        $this->repository = $repository;
    }

    public function __invoke(iterable $collection, array $context): iterable
    {
        unset($context);

        return $collection;
    }
}
