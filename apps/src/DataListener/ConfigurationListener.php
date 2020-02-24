<?php

namespace Labstag\DataListener;

use Doctrine\ORM\Event\LifecycleEventArgs;
use Doctrine\ORM\Events;
use Exception;
use Labstag\Entity\Configuration;
use Psr\Log\LoggerInterface;
use Labstag\Lib\EventSubscriberLib;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class ConfigurationListener extends EventSubscriberLib
{

    /**
     * @var LoggerInterface
     */
    private $logger;

    /**
     * @var Session|SessionInterface
     */
    private $session;


    public function __construct(LoggerInterface $logger, SessionInterface $session)
    {
        $this->logger = $logger;
        /** @var Session $session */
        $this->session = $session;
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
        if (!$entity instanceof Configuration) {
            return;
        }

        $this->traitement($entity);
    }

    public function preUpdate(LifecycleEventArgs $args): void
    {
        $entity = $args->getEntity();
        if (!$entity instanceof Configuration) {
            return;
        }

        $this->traitement($entity);
    }

    private function traitement(Configuration $entity): void
    {
        $name  = $entity->getName();
        $value = $entity->getValue();
        try {
            if ('robotstxt' == $name) {
                file_put_contents('robots.txt', $value);
            }
        } catch (Exception $exception) {
            $this->logger->error($exception->getMessage());
            /** @var Session $session */
            $session = $this->session;
            $session->getFlashBag()->add('danger', (string) $exception->getMessage());
        }
    }
}
