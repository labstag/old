<?php

namespace Labstag\Resolver\Query\Phone;

use ApiPlatform\Core\GraphQl\Resolver\QueryItemResolverInterface;
use Labstag\Entity\Phone;
use Labstag\Repository\PhoneRepository;

final class ItemResolver implements QueryItemResolverInterface
{
    /**
     * @var PhoneRepository
     */
    private $repository;

    public function __construct(PhoneRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * @param Phone|null $item
     *
     * @return Phone
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
