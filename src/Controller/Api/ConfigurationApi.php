<?php

namespace Labstag\Controller\Api;

use Labstag\Lib\ApiControllerLib;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;

class ConfigurationApi extends ApiControllerLib
{
    /**
     * @Route("/api/configuration/trash", name="api_configurationtrash")
     */
    public function trash(Request $request): JsonResponse
    {
        return $this->trashAction($request);
    }
    
    /**
     * @Route("/api/configuration/trash", name="api_configurationtrashdelete", methods={"DELETE"})
     */
    public function delete(Request $request): JsonResponse
    {
        return $this->trashAction($request);
    }
    
    /**
     * @Route("/api/configuration/restore", name="api_configurationrestore", methods={"POST"})
     */
    public function restore(Request $request): JsonResponse
    {
        return $this->trashAction($request);
    }
    
    /**
     * @Route("/api/configuration/empty", name="api_configurationempty", methods={"POST"})
     */
    public function empty(Request $request): JsonResponse
    {
        return $this->trashAction($request);
    }
}
