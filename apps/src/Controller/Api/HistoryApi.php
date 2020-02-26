<?php

namespace Labstag\Controller\Api;

use Knp\Component\Pager\PaginatorInterface;
use Labstag\Entity\History;
use Labstag\Handler\HistoryPublishingHandler;
use Labstag\Lib\ApiControllerLib;
use Labstag\Repository\HistoryRepository;
use Psr\Log\LoggerInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\RouterInterface;

class HistoryApi extends ApiControllerLib
{

    /**
     * @var HistoryPublishingHandler
     */
    protected $publishingHandler;

    public function __construct(
        HistoryPublishingHandler $handler,
        ContainerInterface $container,
        PaginatorInterface $paginator,
        RequestStack $requestStack,
        RouterInterface $router,
        LoggerInterface $logger
    )
    {
        parent::__construct($container, $paginator, $requestStack, $router, $logger);
        $this->publishingHandler = $handler;
    }

    public function __invoke(History $data): History
    {
        $this->publishingHandler->handle($data);

        return $data;
    }

    /**
     * @Route("/api/histories/trash", name="api_historytrash")
     */
    public function trash(HistoryRepository $repository): Response
    {
        return $this->trashAction($repository);
    }

    /**
     * @Route("/api/histories/trash", name="api_historytrashdelete", methods={"DELETE"})
     */
    public function delete(HistoryRepository $repository): JsonResponse
    {
        return $this->deleteAction($repository);
    }

    /**
     * @Route("/api/histories/restore", name="api_historyrestore", methods={"POST"})
     */
    public function restore(HistoryRepository $repository): JsonResponse
    {
        return $this->restoreAction($repository);
    }

    /**
     * @Route("/api/histories/empty", name="api_historyempty", methods={"POST"})
     */
    public function vider(HistoryRepository $repository): JsonResponse
    {
        return $this->emptyAction($repository);
    }
}
