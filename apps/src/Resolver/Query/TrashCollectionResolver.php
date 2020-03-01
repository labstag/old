<?php

namespace Labstag\Resolver\Query;

use ApiPlatform\Core\GraphQl\Resolver\QueryCollectionResolverInterface;

final class TrashCollectionResolver implements QueryCollectionResolverInterface
{
    public function __invoke(iterable $collection, array $context): iterable
    {
        unset($context);
        dump('trashcollection');
        /** @var mixed $collection */
        $query         = $collection->getQuery();
        $entityManager = $query->getEntityManager();
        $filters       = $entityManager->getFilters();
        $enable        = $filters->getEnabledFilters();
        if (isset($enable['softdeleteable'])) {
            $filters->disable('softdeleteable');
        }

        if (!isset($enable['labstag_trash'])) {
            $filters->enable('labstag_trash');
        }

        return $collection;
    }
}
