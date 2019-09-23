<?php

namespace Labstag\DataListener;

use Doctrine\ORM\Event\LifecycleEventArgs;
use Doctrine\ORM\Events;
use Labstag\Entity\Configuration;
use Labstag\Lib\EventSubscriberLib;

class ConfigurationListener extends EventSubscriberLib
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
        $name  = $entity->getName();
        $value = $entity->getValue();
        if ('robotstxt' == $name) {
            file_put_contents('robots.txt', $value);
        }
    }
}
