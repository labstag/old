<?php

namespace Labstag\DataListener;

use Doctrine\ORM\Event\LifecycleEventArgs;
use Doctrine\ORM\Events;
use Labstag\Entity\OauthConnectUser;
use Labstag\Lib\EventSubscriberLib;
use Labstag\Service\OauthService;
use Symfony\Component\DependencyInjection\ContainerInterface;

class OauthConnectUserListener extends EventSubscriberLib
{

    /**
     * @var OauthService
     */
    private $OauthService;

    /**
     * @var ContainerInterface
     */
    private $container;

    public function __construct(ContainerInterface $container, OauthService $OauthService)
    {
        $this->container    = $container;
        $this->OauthService = $OauthService;
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
        $identity = $this->OauthService->getIdentity($data, $name);
        $entity->setIdentity($identity);
    }
}
