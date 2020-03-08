<?php

namespace Labstag\Resolver\Query\Email;

use ApiPlatform\Core\GraphQl\Resolver\QueryCollectionResolverInterface;
use Labstag\Repository\EmailRepository;

final class CollectionResolver implements QueryCollectionResolverInterface
{
    public function __construct(EmailRepository $repository)
    {
        $this->repository = $repository;
    }

    public function __invoke(iterable $collection, array $context): iterable
    {
        unset($context);

        return $collection;
    }
}
