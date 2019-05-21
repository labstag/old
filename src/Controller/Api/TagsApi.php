<?php

namespace Labstag\Controller\Api;

use Labstag\Lib\ApiControllerLib;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;

class TagsApi extends ApiControllerLib
{
    /**
     * @Route("/api/tags/trash", name="api_tagstrash")
     */
    public function trash(Request $request): JsonResponse
    {
        return $this->trashAction($request);
    }
    
    /**
     * @Route("/api/tags/trash", name="api_tagstrashdelete", methods={"DELETE"})
     */
    public function delete(Request $request): JsonResponse
    {
        return $this->trashAction($request);
    }
    
    /**
     * @Route("/api/tags/restore", name="api_tagsrestore", methods={"POST"})
     */
    public function restore(Request $request): JsonResponse
    {
        return $this->trashAction($request);
    }
}
