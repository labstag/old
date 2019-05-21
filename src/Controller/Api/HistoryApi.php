<?php

namespace Labstag\Controller\Api;

use Labstag\Lib\ApiControllerLib;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

class HistoryApi extends ApiControllerLib
{
    /**
     * @Route("/api/history/trash", name="api_historytrash")
     */
    public function trash(): JsonResponse
    {
        return $this->trashAction();
    }

    /**
     * @Route("/api/history/trash", name="api_historytrashdelete", methods={"DELETE"})
     */
    public function delete(): JsonResponse
    {
        return $this->deleteAction();
    }

    /**
     * @Route("/api/history/restore", name="api_historyrestore", methods={"POST"})
     */
    public function restore(): JsonResponse
    {
        return $this->restoreAction();
    }

    /**
     * @Route("/api/history/empty", name="api_historyempty", methods={"POST"})
     */
    public function empty(): JsonResponse
    {
        return $this->emptyAction();
    }
}
