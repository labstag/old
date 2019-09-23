<?php

namespace Labstag\Lib;

use Doctrine\Common\EventSubscriber;
use Labstag\Entity\Configuration;

abstract class EventSubscriberLib implements EventSubscriber
{
    protected function setConfigurationParam($args)
    {
        if (isset($this->configParams)) {
            return;
        }

        $manager    = $args->getEntityManager();
        $repository = $manager->getRepository(Configuration::class);
        $data       = $repository->findAll();
        $config     = [];
        foreach ($data as $row) {
            $key          = $row->getName();
            $value        = $row->getValue();
            $config[$key] = $value;
        }

        $this->configParams = $config;
    }
}
