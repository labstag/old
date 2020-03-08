<?php

namespace Labstag\Resolver\Query\User;

use ApiPlatform\Core\GraphQl\Resolver\QueryCollectionResolverInterface;
use Labstag\Repository\UserRepository;

final class CollectionResolver implements QueryCollectionResolverInterface
{
    public function __construct(UserRepository $repository)
    {
        $this->repository = $repository;
    }

    public function __invoke(iterable $collection, array $context): iterable
    {
        unset($context);

        return $collection;
    }
}
