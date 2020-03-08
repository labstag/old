<?php

namespace Labstag\Resolver\Query\OauthConnectUser;

use ApiPlatform\Core\GraphQl\Resolver\QueryCollectionResolverInterface;
use Labstag\Repository\OauthConnectUserRepository;

final class CollectionResolver implements QueryCollectionResolverInterface
{
    public function __construct(OauthConnectUserRepository $repository)
    {
        $this->repository = $repository;
    }

    public function __invoke(iterable $collection, array $context): iterable
    {
        unset($context);

        return $collection;
    }
}
