<?php

namespace Labstag\Resolver\Query\Post;

use ApiPlatform\Core\GraphQl\Resolver\QueryCollectionResolverInterface;

final class EnableResolver implements QueryCollectionResolverInterface
{
    public function __invoke(iterable $collection, array $context): iterable
    {
        unset($context);

        return $collection;
    }
}
