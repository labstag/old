<?php

namespace Labstag\Controller\Api;

use Labstag\Lib\ApiControllerLib;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Labstag\Repository\ConfigurationRepository;

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
