<?php

namespace Labstag\Resolver;

use ApiPlatform\Core\GraphQl\Resolver\QueryCollectionResolverInterface;

final class TrashCollectionResolver implements QueryCollectionResolverInterface
{
    /**
     * @param iterable $collection
     *
     * @return iterable
     */
    public function __invoke(iterable $collection, array $context): iterable
    {
        unset($content);
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
