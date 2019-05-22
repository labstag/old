<?php

namespace Labstag\Controller\Api;

use Labstag\Lib\ApiControllerLib;
use Labstag\Repository\ConfigurationRepository;
use Symfony\Component\Routing\Annotation\Route;

class ConfigurationApi extends ApiControllerLib
{
    /**
     * @Route("/api/configurations/trash.{_format}", name="api_configurationtrash")
     */
    public function trash(ConfigurationRepository $repository, $_format)
    {
        return $this->trashAction($repository, $_format);
    }

    /**
     * @Route("/api/configurations/trash.{_format}", name="api_configurationtrashdelete", methods={"DELETE"})
     */
    public function delete(ConfigurationRepository $repository, $_format)
    {
        return $this->trashAction($repository, $_format);
    }

    /**
     * @Route("/api/configurations/restore.{_format}", name="api_configurationrestore", methods={"POST"})
     */
    public function restore(ConfigurationRepository $repository, $_format)
    {
        return $this->restoreAction($repository, $_format);
    }

    /**
     * @Route("/api/configurations/empty.{_format}", name="api_configurationempty", methods={"POST"})
     */
    public function empty(ConfigurationRepository $repository, $_format)
    {
        return $this->emptyAction($repository, $_format);
    }
}
