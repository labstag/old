<?php

namespace Labstag\Controller\Api;

use Labstag\Entity\Configuration;
use Labstag\Lib\ApiControllerLib;
use Labstag\Repository\ConfigurationRepository;
use Labstag\Handler\ConfigurationPublishingHandler;
use Symfony\Component\Routing\Annotation\Route;

class ConfigurationApi extends ApiControllerLib
{

    public function __construct(ConfigurationPublishingHandler $configurationPublishingHandler)
    {
        $this->configurationPublishingHandler = $configurationPublishingHandler;
    }

    public function __invoke(Configuration $data): Configuration
    {
        $this->configurationPublishingHandler->handle($data);

        return $data;
    }
    
    
    /**
     * @Route("/api/configurations/trash", name="api_configurationtrash")
     *
     * @param string $_format
     */
    public function trash(ConfigurationRepository $repository)
    {
        return $this->trashAction($repository);
    }

    /**
     * @Route("/api/configurations/trash", name="api_configurationtrashdelete", methods={"DELETE"})
     *
     * @param string $_format
     */
    public function delete(ConfigurationRepository $repository)
    {
        return $this->deleteAction($repository);
    }

    /**
     * @Route("/api/configurations/restore", name="api_configurationrestore", methods={"POST"})
     *
     * @param string $_format
     */
    public function restore(ConfigurationRepository $repository)
    {
        return $this->restoreAction($repository);
    }

    /**
     * @Route("/api/configurations/empty", name="api_configurationempty", methods={"POST"})
     *
     * @param string $_format
     */
    public function vider(ConfigurationRepository $repository)
    {
        return $this->emptyAction($repository);
    }
}
