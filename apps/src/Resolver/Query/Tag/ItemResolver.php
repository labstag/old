<?php

namespace Labstag\Resolver\Query\Tag;

use ApiPlatform\Core\GraphQl\Resolver\QueryItemResolverInterface;
use Labstag\Entity\Tag;
use Labstag\Repository\TagRepository;

final class ItemResolver implements QueryItemResolverInterface
{
    /**
     * @var TagRepository
     */
    private $repository;

    public function __construct(TagRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * @param Tag|null $item
     *
     * @return Tag
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
