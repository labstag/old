<?php

namespace Labstag\DataListener;

use Doctrine\ORM\Event\LifecycleEventArgs;
use Doctrine\ORM\Events;
use Labstag\Entity\OauthConnectUser;
use Labstag\Lib\EventSubscriberLib;
use Labstag\Services\OauthServices;
use Symfony\Component\DependencyInjection\ContainerInterface;

class OauthConnectUserListener extends EventSubscriberLib
{

    /**
     * @var OauthServices
     */
    private $oauthServices;

    /**
     * @var ContainerInterface
     */
    private $container;

    public function __construct(ContainerInterface $container, OauthServices $oauthServices)
    {
        $this->container     = $container;
        $this->oauthServices = $oauthServices;
    }

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
        if (!$entity instanceof OauthConnectUser) {
            return;
        }

        $this->setIdentity($entity);
        // $manager  = $args->getEntityManager();
        // $meta = $manager->getClassMetadata(get_class($entity));
        // $manager->getUnitOfWork()->recomputeSingleEntityChangeSet($meta, $entity);
    }

    public function preUpdate(LifecycleEventArgs $args)
    {
        $entity = $args->getEntity();
        if (!$entity instanceof OauthConnectUser) {
            return;
        }

        $this->setIdentity($entity);
        $manager = $args->getEntityManager();
        $meta    = $manager->getClassMetadata(get_class($entity));
        $manager->getUnitOfWork()->recomputeSingleEntityChangeSet($meta, $entity);
    }

    private function setIdentity(OauthConnectUser $entity)
    {
        $name     = $entity->getName();
        $data     = $entity->getData();
        $identity = $this->oauthServices->getIdentity($data, $name);
        $entity->setIdentity($identity);
    }
}
