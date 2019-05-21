<?php

namespace Labstag\DataListener;

use Doctrine\Common\EventSubscriber;
use Doctrine\ORM\Event\OnFlushEventArgs;
use Doctrine\ORM\Events;
use Labstag\Entity\Chapitre;
use Labstag\Entity\History;

class ChapitreListener implements EventSubscriber
{
    /**
     * Sur quoi écouter.
     *
     * @return array
     */
    public function getSubscribedEvents()
    {
        return [
            Events::onFlush,
        ];
    }

    public function onFlush(OnFlushEventArgs $args)
    {
        $manager = $args->getEntityManager();
        $uow     = $manager->getUnitOfWork();

        foreach ($uow->getScheduledEntityUpdates() as $entity) {
            if (!$entity instanceof Chapitre) {
                return;
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
