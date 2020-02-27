<?php

namespace Labstag\Resolver;

use ApiPlatform\Core\GraphQl\Resolver\QueryItemResolverInterface;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;

final class TrashResolver implements QueryItemResolverInterface
{

    /**
     * @var EntityManager | EntityManagerInterface
     */
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }
    /**
     * @param mixed|null $item
     *
     * @return mixed
     */
    public function __invoke($item, array $context)
    {
        $filters = $this->entityManager->getFilters();
        $filters->disable('softdeleteable');
        $filters->enable('labstag_trash');
        
        // Query arguments are in $context['args'].

        // Do something with the book.
        // Or fetch the book if it has not been retrieved.

        return $item;
    }
}