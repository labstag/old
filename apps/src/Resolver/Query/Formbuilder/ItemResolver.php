<?php

namespace Labstag\Resolver\Query\Formbuilder;

use ApiPlatform\Core\GraphQl\Resolver\QueryItemResolverInterface;
use Labstag\Entity\Formbuilder;
use Labstag\Repository\FormbuilderRepository;

final class ItemResolver implements QueryItemResolverInterface
{
    /**
     * @var FormbuilderRepository
     */
    private $repository;

    public function __construct(FormbuilderRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * @param Formbuilder|null $item
     *
     * @return Formbuilder
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
