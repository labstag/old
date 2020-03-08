<?php

namespace Labstag\Resolver\Query\Edito;

use ApiPlatform\Core\GraphQl\Resolver\QueryItemResolverInterface;
use Labstag\Entity\Edito;
use Labstag\Repository\EditoRepository;

final class ItemResolver implements QueryItemResolverInterface
{
    /**
     * @var EditoRepository
     */
    private $repository;

    public function __construct(EditoRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * @param Edito|null $item
     *
     * @return Edito
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
