<?php

namespace Labstag\Controller\Api;

use Labstag\Lib\ApiControllerLib;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

class ConfigurationApi extends ApiControllerLib
{
    /**
     * @Route("/api/configuration/trash", name="api_configurationtrash")
     */
    public function trash(): JsonResponse
    {
        return $this->trashAction();
    }

    /**
     * @Route("/api/configuration/trash", name="api_configurationtrashdelete", methods={"DELETE"})
     */
    public function delete(): JsonResponse
    {
        return $this->trashAction();
    }

    /**
     * @Route("/api/configuration/restore", name="api_configurationrestore", methods={"POST"})
     */
    public function restore(): JsonResponse
    {
        return $this->restoreAction();
    }

    /**
     * @Route("/api/configuration/empty", name="api_configurationempty", methods={"POST"})
     */
    public function empty(): JsonResponse
    {
        return $this->emptyAction();
    }
}
