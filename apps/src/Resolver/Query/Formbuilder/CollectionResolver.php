<?php

namespace Labstag\Resolver\Query\Formbuilder;

use ApiPlatform\Core\GraphQl\Resolver\QueryCollectionResolverInterface;
use Labstag\Repository\FormbuilderRepository;

final class CollectionResolver implements QueryCollectionResolverInterface
{
    public function __construct(FormbuilderRepository $repository)
    {
        $this->repository = $repository;
    }

    public function __invoke(iterable $collection, array $context): iterable
    {
        unset($context);

        return $collection;
    }
}
