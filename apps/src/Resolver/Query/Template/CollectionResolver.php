<?php

namespace Labstag\Resolver\Query\Template;

use ApiPlatform\Core\GraphQl\Resolver\QueryCollectionResolverInterface;
use Labstag\Repository\TemplateRepository;

final class CollectionResolver implements QueryCollectionResolverInterface
{
    public function __construct(TemplateRepository $repository)
    {
        $this->repository = $repository;
    }

    public function __invoke(iterable $collection, array $context): iterable
    {
        unset($context);

        return $collection;
    }
}
