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
    private $oauthService;

    /**
     * @var ContainerInterface
     */
    private $container;

    public function __construct(
        ContainerInterface $container,
        OauthService $oauthService
    )
    {
        $this->container    = $container;
        $this->oauthService = $oauthService;
    }

    /**
     * Sur quoi écouter.
     */
    public function getSubscribedEvents(): array
    {
        return [
            Events::preUpdate,
            Events::prePersist,
        ];
    }

    public function prePersist(LifecycleEventArgs $args): void
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

    public function preUpdate(LifecycleEventArgs $args): void
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

    private function setIdentity(OauthConnectUser $entity): void
    {
        $name = $entity->getName();
        $data = $entity->getData();
        /** @var string $identity */
        $identity = $this->oauthService->getIdentity($data, $name);
        $entity->setIdentity($identity);
    }
}
