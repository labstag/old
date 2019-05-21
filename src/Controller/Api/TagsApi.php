<?php

namespace Labstag\Controller\Api;

use Labstag\Lib\ApiControllerLib;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

class TagsApi extends ApiControllerLib
{
    /**
     * @Route("/api/tags/trash", name="api_tagstrash")
     */
    public function trash(): JsonResponse
    {
        return $this->trashAction();
    }

    /**
     * @Route("/api/tags/trash", name="api_tagstrashdelete", methods={"DELETE"})
     */
    public function delete(): JsonResponse
    {
        return $this->deleteAction();
    }

    /**
     * @Route("/api/tags/restore", name="api_tagsrestore", methods={"POST"})
     */
    public function restore(): JsonResponse
    {
        return $this->restoreAction();
    }

    /**
     * @Route("/api/tags/empty", name="api_tagsempty", methods={"POST"})
     */
    public function empty(): JsonResponse
    {
        return $this->emptyAction();
    }
}
