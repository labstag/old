<?php

namespace Labstag\Resolver;

use ApiPlatform\Core\GraphQl\Resolver\QueryCollectionResolverInterface;

final class CollectionResolver implements QueryCollectionResolverInterface
{
    /**
     * @param iterable $collection
     *
     * @return iterable
     */
    public function __invoke(iterable $collection, array $context): iterable
    {
        unset($content);
        return $collection;
    }
}
