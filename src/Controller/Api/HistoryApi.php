<?php

namespace Labstag\Controller\Api;

use Labstag\Entity\History;
use Labstag\Lib\ApiControllerLib;
use Labstag\Repository\HistoryRepository;
use Labstag\Handler\HistoryPublishingHandler;
use Symfony\Component\Routing\Annotation\Route;

class HistoryApi extends ApiControllerLib
{

    public function __construct(HistoryPublishingHandler $historyPublishingHandler)
    {
        $this->historyPublishingHandler = $historyPublishingHandler;
    }

    public function __invoke(History $data): History
    {
        $this->historyPublishingHandler->handle($data);

        return $data;
    }
    /**
     * @Route("/api/histories/trash", name="api_historytrash")
     *
     * @param string $_format
     */
    public function trash(HistoryRepository $repository)
    {
        return $this->trashAction($repository);
    }

    /**
     * @Route("/api/histories/trash", name="api_historytrashdelete", methods={"DELETE"})
     *
     * @param string $_format
     */
    public function delete(HistoryRepository $repository)
    {
        return $this->deleteAction($repository);
    }

    /**
     * @Route("/api/histories/restore", name="api_historyrestore", methods={"POST"})
     *
     * @param string $_format
     */
    public function restore(HistoryRepository $repository)
    {
        return $this->restoreAction($repository);
    }

    /**
     * @Route("/api/histories/empty", name="api_historyempty", methods={"POST"})
     *
     * @param string $_format
     */
    public function vider(HistoryRepository $repository)
    {
        return $this->emptyAction($repository);
    }
}
