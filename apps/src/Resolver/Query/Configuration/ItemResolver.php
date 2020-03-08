<?php

namespace Labstag\Resolver\Query\Configuration;

use ApiPlatform\Core\GraphQl\Resolver\QueryItemResolverInterface;
use Labstag\Entity\Configuration;
use Labstag\Repository\ConfigurationRepository;

final class ItemResolver implements QueryItemResolverInterface
{
    /**
     * @var ConfigurationRepository
     */
    private $repository;

    public function __construct(ConfigurationRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * @param Configuration|null $item
     *
     * @return Configuration
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
