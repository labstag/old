<?php

namespace Labstag\Controller\Api;

use Labstag\Lib\ApiControllerLib;
use Labstag\Repository\HistoryRepository;
use Symfony\Component\Routing\Annotation\Route;

class HistoryApi extends ApiControllerLib
{
    /**
     * @Route("/api/histories/trash.{_format}", name="api_historytrash")
     *
     * @param string $_format
     */
    public function trash(HistoryRepository $repository, $_format)
    {
        return $this->trashAction($repository, $_format);
    }

    /**
     * @Route("/api/histories/trash.{_format}", name="api_historytrashdelete", methods={"DELETE"})
     *
     * @param string $_format
     */
    public function delete(HistoryRepository $repository, $_format)
    {
        return $this->deleteAction($repository, $_format);
    }

    /**
     * @Route("/api/histories/restore.{_format}", name="api_historyrestore", methods={"POST"})
     *
     * @param string $_format
     */
    public function restore(HistoryRepository $repository, $_format)
    {
        return $this->restoreAction($repository, $_format);
    }

    /**
     * @Route("/api/histories/empty.{_format}", name="api_historyempty", methods={"POST"})
     *
     * @param string $_format
     */
    public function empty(HistoryRepository $repository, $_format)
    {
        return $this->emptyAction($repository, $_format);
    }
}
