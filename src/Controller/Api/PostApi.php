<?php

namespace Labstag\Controller\Api;

use Labstag\Lib\ApiControllerLib;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

class PostApi extends ApiControllerLib
{
    /**
     * @Route("/api/post/trash", name="api_posttrash")
     */
    public function trash(): JsonResponse
    {
        return $this->trashAction();
    }

    /**
     * @Route("/api/post/trash", name="api_posttrashdelete", methods={"DELETE"})
     */
    public function delete(): JsonResponse
    {
        return $this->deleteAction();
    }

    /**
     * @Route("/api/post/restore", name="api_postrestore", methods={"POST"})
     */
    public function restore(): JsonResponse
    {
        return $this->restoreAction();
    }

    /**
     * @Route("/api/post/empty", name="api_postempty", methods={"POST"})
     */
    public function empty(): JsonResponse
    {
        return $this->emptyAction();
    }
}
