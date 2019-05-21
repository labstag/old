<?php

namespace Labstag\Controller\Api;

use Labstag\Lib\ApiControllerLib;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;

class ChapitreApi extends ApiControllerLib
{
    /**
     * @Route("/api/chapitre/trash", name="api_chapitretrash")
     */
    public function trash(Request $request): JsonResponse
    {
        return $this->trashAction($request);
    }
    
    /**
     * @Route("/api/chapitre/trash", name="api_chapitretrashdelete", methods={"DELETE"})
     */
    public function delete(Request $request): JsonResponse
    {
        return $this->trashAction($request);
    }
    
    /**
     * @Route("/api/chapitre/restore", name="api_chapitrerestore", methods={"POST"})
     */
    public function restore(Request $request): JsonResponse
    {
        return $this->trashAction($request);
    }
    
    /**
     * @Route("/api/chapitre/empty", name="api_chapitreempty", methods={"POST"})
     */
    public function empty(Request $request): JsonResponse
    {
        return $this->trashAction($request);
    }
}
