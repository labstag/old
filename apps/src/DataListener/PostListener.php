<?php

namespace Labstag\DataListener;

use Doctrine\ORM\Event\OnFlushEventArgs;
use Doctrine\ORM\Events;
use Labstag\Entity\Post;
use Labstag\Entity\Tag;
use Labstag\Lib\EventSubscriberLib;

class PostListener extends EventSubscriberLib
{
    /**
     * Sur quoi écouter.
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
            if (!$entity instanceof Post) {
                continue;
            }

            $tags = $entity->getTags();
            foreach ($tags as $tag) {
                /** @var Tag $tag */
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
