<?php

namespace Labstag\Resolver\Query\History;

use ApiPlatform\Core\GraphQl\Resolver\QueryItemResolverInterface;
use Labstag\Entity\History;
use Labstag\Repository\HistoryRepository;

final class ItemResolver implements QueryItemResolverInterface
{

    /**
     * @var HistoryRepository
     */
    private $repository;

    public function __construct(HistoryRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * @param History|null $item
     *
     * @return History
     */
    public function __invoke($item, array $context)
    {
        $query = $this->repository->findAllActive($context);
        $query->setMaxResults(1);
        $item = $query->getQuery()->getOneOrNullResult();
        unset($context);

        // Query arguments are in $context['args'].

        // Do something with the book.
        // Or fetch the book if it has not been retrieved.

        return $item;
    }
}
