<?php

namespace Labstag\Controller\Api;

use Labstag\Lib\ApiControllerLib;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Labstag\Repository\HistoryRepository;

class HistoryApi extends ApiControllerLib
{
    /**
     * @Route("/api/histories/trash.{_format}", name="api_historytrash")
     */
    public function trash(HistoryRepository $repository, $_format)
    {
        return $this->trashAction($repository, $_format);
    }

    /**
     * @Route("/api/histories/trash.{_format}", name="api_historytrashdelete", methods={"DELETE"})
     */
    public function delete(HistoryRepository $repository, $_format)
    {
        return $this->deleteAction($repository, $_format);
    }

    /**
     * @Route("/api/histories/restore.{_format}", name="api_historyrestore", methods={"POST"})
     */
    public function restore(HistoryRepository $repository, $_format)
    {
        return $this->restoreAction($repository, $_format);
    }

    /**
     * @Route("/api/histories/empty.{_format}", name="api_historyempty", methods={"POST"})
     */
    public function empty(HistoryRepository $repository, $_format)
    {
        return $this->emptyAction($repository, $_format);
    }
}
