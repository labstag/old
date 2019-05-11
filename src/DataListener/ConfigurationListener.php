<?php

namespace Labstag\DataListener;

use Doctrine\Common\EventSubscriber;
use Doctrine\ORM\Events;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Labstag\Entity\Configuration;

class ConfigurationListener implements EventSubscriber
{
    /**
     * Sur quoi écouter.
     *
     * @return array
     */
    public function getSubscribedEvents()
    {
        return [
            Events::preUpdate,
            Events::prePersist,
        ];
    }


    public function prePersist(LifecycleEventArgs $args)
    {
        $entity = $args->getEntity();
        if (!$entity instanceof Configuration) {
            return;
        }

        $this->traitement($entity);
    }

    public function preUpdate(LifecycleEventArgs $args)
    {
        $entity = $args->getEntity();
        if (!$entity instanceof Configuration) {
            return;
        }

        $this->traitement($entity);
    }

    private function traitement($entity)
    {
        $name = $entity->getName();
        $value = $entity->getValue();
        if ($name == "robotstxt") {
            file_put_contents('robots.txt', $value);
        }
    }
}
