<?php

namespace Labstag\Resolver\Query\Bookmark;

use ApiPlatform\Core\GraphQl\Resolver\QueryItemResolverInterface;
use Labstag\Entity\Bookmark;
use Labstag\Repository\BookmarkRepository;

final class ItemEnableResolver implements QueryItemResolverInterface
{
    /**
     * @var BookmarkRepository
     */
    private $repository;

    public function __construct(BookmarkRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * @param Bookmark|null $item
     *
     * @return Bookmark
     */
    public function __invoke($item, array $context)
    {
        $query = $this->repository->findAllActive();
        $query->setMaxResults(1);
        $item = $query->getQuery()->getOneOrNullResult();
        unset($context);

        // Query arguments are in $context['args'].

        // Do something with the book.
        // Or fetch the book if it has not been retrieved.

        return $item;
    }
}
