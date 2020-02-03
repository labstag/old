<?php

namespace Labstag\DataListener;

use Doctrine\ORM\Event\OnFlushEventArgs;
use Doctrine\ORM\Events;
use Labstag\Entity\Bookmark;
use Labstag\Entity\Tags;
use Labstag\Lib\EventSubscriberLib;

class BookmarkListener extends EventSubscriberLib
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
            if (!$entity instanceof Bookmark) {
                continue;
            }

            $tags = $entity->getTags();
            foreach ($tags as $tag) {
                /** @var Tags $tag */
                if ($tag->isTemporary()) {
                    $tag->setTemporary(false);
                    $manager->persist($tag);
                    $meta = $manager->getClassMetadata(get_class($tag));
                    $manager->getUnitOfWork()->computeChangeSet($meta, $tag);
                }
            }
        }
    }
}
