<?php

namespace Labstag\DataListener;

use Doctrine\Common\EventSubscriber;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Doctrine\ORM\Events;
use Labstag\Entity\OauthConnectUser;
use Labstag\Services\OauthServices;
use Symfony\Component\DependencyInjection\ContainerInterface;

class OauthConnectUserListener implements EventSubscriber
{

    /**
     * @var OauthServices
     */
    private $oauthServices;

    /**
     * @var ContainerInterface
     */
    private $container;

    public function __construct(ContainerInterface $container)
    {
        $this->container     = $container;
        $this->oauthServices = $this->container->get(OauthServices::class);
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
        // $enm  = $args->getEntityManager();
        // $meta = $enm->getClassMetadata(get_class($entity));
        // $enm->getUnitOfWork()->recomputeSingleEntityChangeSet($meta, $entity);
    }

    public function preUpdate(LifecycleEventArgs $args)
    {
        $entity = $args->getEntity();
        if (!$entity instanceof OauthConnectUser) {
            return;
        }

        $this->setIdentity($entity);
        $enm  = $args->getEntityManager();
        $meta = $enm->getClassMetadata(get_class($entity));
        $enm->getUnitOfWork()->recomputeSingleEntityChangeSet($meta, $entity);
    }

    private function setIdentity(OauthConnectUser $entity)
    {
        $name     = $entity->getName();
        $data     = $entity->getData();
        $identity = $this->oauthServices->getIdentity($data, $name);
        $entity->setIdentity($identity);
    }
}
