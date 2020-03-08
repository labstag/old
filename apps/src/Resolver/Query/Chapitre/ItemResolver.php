<?php

namespace Labstag\Resolver\Query\Chapitre;

use ApiPlatform\Core\GraphQl\Resolver\QueryItemResolverInterface;
use Labstag\Entity\Chapitre;
use Labstag\Repository\ChapitreRepository;

final class ItemResolver implements QueryItemResolverInterface
{

    /**
     * @var ChapitreRepository
     */
    private $repository;

    public function __construct(ChapitreRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * @param Chapitre|null $item
     *
     * @return Chapitre
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
