<?php

namespace Labstag\Resolver\Query;

use ApiPlatform\Core\GraphQl\Resolver\QueryCollectionResolverInterface;

final class CollectionResolver implements QueryCollectionResolverInterface
{
    public function __invoke(iterable $collection, array $context): iterable
    {
        unset($context);

        return $collection;
    }
}
