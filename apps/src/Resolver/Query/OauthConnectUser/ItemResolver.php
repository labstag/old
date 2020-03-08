<?php

namespace Labstag\Resolver\Query\OauthConnectUser;

use ApiPlatform\Core\GraphQl\Resolver\QueryItemResolverInterface;
use Labstag\Entity\OauthConnectUser;
use Labstag\Repository\OauthConnectUserRepository;

final class ItemResolver implements QueryItemResolverInterface
{
    /**
     * @var OauthConnectUserRepository
     */
    private $repository;

    public function __construct(OauthConnectUserRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * @param OauthConnectUser|null $item
     *
     * @return OauthConnectUser
     */
    public function __invoke($item, array $context)
    {
        unset($context);

        // Query arguments are in $context['args'].

        // Do something with the book.
        // Or fetch the book if it has not been retrieved.

        return $item;
    }
}
