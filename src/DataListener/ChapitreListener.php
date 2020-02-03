<?php

namespace Labstag\DataListener;

use Doctrine\ORM\Event\OnFlushEventArgs;
use Doctrine\ORM\Events;
use Labstag\Entity\Chapitre;
use Labstag\Entity\History;
use Labstag\Lib\EventSubscriberLib;

class ChapitreListener extends EventSubscriberLib
{
    /**
     * Sur quoi écouter.
     *
     * @return array
     */
    public function getSubscribedEvents(): array
    {
        return [
            Events::onFlush,
        ];
    }

    public function onFlush(OnFlushEventArgs $args): void
    {
        $manager       = $args->getEntityManager();
        $uow           = $manager->getUnitOfWork();
        $entityUpdates = $uow->getScheduledEntityUpdates();
        foreach ($entityUpdates as $entity) {
            if (!$entity instanceof Chapitre) {
                continue;
            }

            /** @var History */
            $history = $entity->getRefhistory();
            $history->setUpdatedAt($entity->getUpdatedAt());
            $manager->persist($history);
            $meta = $manager->getClassMetadata(get_class($history));
            $manager->getUnitOfWork()->computeChangeSet($meta, $history);
        }
    }
}
