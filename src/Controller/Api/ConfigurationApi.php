<?php

namespace Labstag\Controller\Api;

use Labstag\Lib\ApiControllerLib;
use Labstag\Repository\ConfigurationRepository;
use Symfony\Component\Routing\Annotation\Route;

class ConfigurationApi extends ApiControllerLib
{
    /**
     * @Route("/api/configurations/trash.{_format}", name="api_configurationtrash")
     *
     * @param string $format
     */
    public function trash(ConfigurationRepository $repository, $format)
    {
        return $this->trashAction($repository, $format);
    }

    /**
     * @Route("/api/configurations/trash.{_format}", name="api_configurationtrashdelete", methods={"DELETE"})
     *
     * @param string $format
     */
    public function delete(ConfigurationRepository $repository, $format)
    {
        return $this->deleteAction($repository, $format);
    }

    /**
     * @Route("/api/configurations/restore.{_format}", name="api_configurationrestore", methods={"POST"})
     *
     * @param string $format
     */
    public function restore(ConfigurationRepository $repository, $format)
    {
        return $this->restoreAction($repository, $format);
    }

    /**
     * @Route("/api/configurations/empty.{_format}", name="api_configurationempty", methods={"POST"})
     *
     * @param string $format
     */
    public function vider(ConfigurationRepository $repository, $format)
    {
        return $this->emptyAction($repository, $format);
    }
}
