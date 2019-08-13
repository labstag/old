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
     * @param string $format
     */
    public function trash(HistoryRepository $repository, $format)
    {
        return $this->trashAction($repository, $format);
    }

    /**
     * @Route("/api/histories/trash.{_format}", name="api_historytrashdelete", methods={"DELETE"})
     *
     * @param string $format
     */
    public function delete(HistoryRepository $repository, $format)
    {
        return $this->deleteAction($repository, $format);
    }

    /**
     * @Route("/api/histories/restore.{_format}", name="api_historyrestore", methods={"POST"})
     *
     * @param string $format
     */
    public function restore(HistoryRepository $repository, $format)
    {
        return $this->restoreAction($repository, $format);
    }

    /**
     * @Route("/api/histories/empty.{_format}", name="api_historyempty", methods={"POST"})
     *
     * @param string $format
     */
    public function vider(HistoryRepository $repository, $format)
    {
        return $this->emptyAction($repository, $format);
    }
}
