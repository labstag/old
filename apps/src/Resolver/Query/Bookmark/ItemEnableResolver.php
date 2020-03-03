<?php

namespace Labstag\Resolver\Query\Bookmark;

use ApiPlatform\Core\GraphQl\Resolver\QueryItemResolverInterface;

final class ItemEnableResolver implements QueryItemResolverInterface
{
    /**
     * @param mixed|null $item
     *
     * @return mixed
     */
    public function __invoke($item, array $context)
    {
        unset($context);
        dump('trash');

        // Query arguments are in $context['args'].

        // Do something with the book.
        // Or fetch the book if it has not been retrieved.

        return $item;
    }
}
