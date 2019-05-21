<?php

namespace Labstag\Controller\Api;

use Labstag\Lib\ApiControllerLib;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;

class UserApi extends ApiControllerLib
{
    /**
     * @Route("/api/user/trash", name="api_usertrash")
     */
    public function check(Request $request): JsonResponse
    {
        return $this->trashAction($request);
    }
    
    /**
     * @Route("/api/user/trash", name="api_usertrashdelete", methods={"DELETE"})
     */
    public function delete(Request $request): JsonResponse
    {
        return $this->trashAction($request);
    }
    
    /**
     * @Route("/api/user/restore", name="api_userrestore", methods={"POST"})
     */
    public function restore(Request $request): JsonResponse
    {
        return $this->trashAction($request);
    }
    
    /**
     * @Route("/api/user/empty", name="api_userempty", methods={"POST"})
     */
    public function empty(Request $request): JsonResponse
    {
        return $this->trashAction($request);
    }
}
