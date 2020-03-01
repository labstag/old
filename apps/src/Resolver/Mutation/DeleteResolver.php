<?php

namespace Labstag\Resolver\Mutation;

use ApiPlatform\Core\GraphQl\Resolver\MutationResolverInterface;

final class DeleteResolver implements MutationResolverInterface
{
    /**
     * @param mixed|null $item
     *
     * @return mixed
     */
    public function __invoke($item, array $context)
    {
        unset($context);
        // Mutation input arguments are in $context['args']['input'].

        // Do something with the book.
        // Or fetch the book if it has not been retrieved.

        // The returned item will pe persisted.
        return $item;
    }
}
