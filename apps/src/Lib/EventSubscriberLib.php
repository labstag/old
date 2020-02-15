<?php

namespace Labstag\Lib;

use Doctrine\Common\EventSubscriber;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Labstag\Entity\Configuration;
use Labstag\Repository\ConfigurationRepository;

abstract class EventSubscriberLib implements EventSubscriber
{

    /**
     * @var array
     */
    protected $configParams;

    protected function setConfigurationParam(LifecycleEventArgs $args): void
    {
        if (isset($this->configParams)) {
            return;
        }

        $manager = $args->getEntityManager();
        /** @var ConfigurationRepository $repository */
        $repository = $manager->getRepository(Configuration::class);
        $data       = $repository->findAll();
        $config     = [];
        /** @var Configuration $row */
        foreach ($data as $row) {
            $key          = $row->getName();
            $value        = $row->getValue();
            $config[$key] = $value;
        }

        $this->configParams = $config;
    }
}
