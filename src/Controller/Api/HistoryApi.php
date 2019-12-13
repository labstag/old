<?php

namespace Labstag\Controller\Api;

use Knp\Component\Pager\PaginatorInterface;
use Labstag\Entity\History;
use Labstag\Handler\HistoryPublishingHandler;
use Labstag\Lib\ApiControllerLib;
use Labstag\Repository\HistoryRepository;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\RouterInterface;

class HistoryApi extends ApiControllerLib
{
    public function __construct(
        HistoryPublishingHandler $handler,
        ContainerInterface $container,
        PaginatorInterface $paginator,
        RequestStack $requestStack,
        RouterInterface $router
    )
    {
        $this->historyPublishingHandler = $handler;
    }

    public function __invoke(History $data): History
    {
        $this->historyPublishingHandler->handle($data);

        return $data;
    }

    /**
     * @Route("/api/histories/trash", name="api_historytrash")
     */
    public function trash(HistoryRepository $repository)
    {
        return $this->trashAction($repository);
    }

    /**
     * @Route("/api/histories/trash", name="api_historytrashdelete", methods={"DELETE"})
     */
    public function delete(HistoryRepository $repository)
    {
        return $this->deleteAction($repository);
    }

    /**
     * @Route("/api/histories/restore", name="api_historyrestore", methods={"POST"})
     */
    public function restore(HistoryRepository $repository)
    {
        return $this->restoreAction($repository);
    }

    /**
     * @Route("/api/histories/empty", name="api_historyempty", methods={"POST"})
     */
    public function vider(HistoryRepository $repository)
    {
        return $this->emptyAction($repository);
    }
}
