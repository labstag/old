<?php

namespace Labstag\DataListener;

use Doctrine\Common\EventSubscriber;
use Doctrine\ORM\Event\OnFlushEventArgs;
use Doctrine\ORM\Events;
use Labstag\Entity\Bookmark;
use Labstag\Entity\Tags;

class BookmarkListener implements EventSubscriber
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
            if (!$entity instanceof Bookmark) {
                return;
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
