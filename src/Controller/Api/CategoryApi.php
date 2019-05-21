<?php

namespace Labstag\Controller\Api;

use Labstag\Lib\ApiControllerLib;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;

class CategoryApi extends ApiControllerLib
{
    /**
     * @Route("/api/category/trash", name="api_categorytrash")
     */
    public function trash(Request $request): JsonResponse
    {
        return $this->trashAction($request);
    }
    
    /**
     * @Route("/api/category/trash", name="api_categorytrashdelete", methods={"DELETE"})
     */
    public function delete(Request $request): JsonResponse
    {
        return $this->trashAction($request);
    }
    
    /**
     * @Route("/api/category/restore", name="api_categoryrestore", methods={"POST"})
     */
    public function restore(Request $request): JsonResponse
    {
        return $this->trashAction($request);
    }
    
    /**
     * @Route("/api/category/empty", name="api_categoryempty", methods={"POST"})
     */
    public function empty(Request $request): JsonResponse
    {
        return $this->trashAction($request);
    }
}
