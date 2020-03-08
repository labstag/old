<?php

namespace Labstag\Resolver\Query\Tag;

use ApiPlatform\Core\GraphQl\Resolver\QueryCollectionResolverInterface;
use Labstag\Repository\TagRepository;

final class CollectionResolver implements QueryCollectionResolverInterface
{
    public function __construct(TagRepository $repository)
    {
        $this->repository = $repository;
    }

    public function __invoke(iterable $collection, array $context): iterable
    {
        unset($context);

        return $collection;
    }
}
