<?php

namespace Labstag\Resolver\Query\Phone;

use ApiPlatform\Core\GraphQl\Resolver\QueryCollectionResolverInterface;
use Labstag\Repository\PhoneRepository;

final class CollectionResolver implements QueryCollectionResolverInterface
{
    public function __construct(PhoneRepository $repository)
    {
        $this->repository = $repository;
    }

    public function __invoke(iterable $collection, array $context): iterable
    {
        unset($context);

        return $collection;
    }
}
