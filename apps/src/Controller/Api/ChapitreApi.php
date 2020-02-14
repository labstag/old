<?php

namespace Labstag\Controller\Api;

use Knp\Component\Pager\PaginatorInterface;
use Labstag\Entity\Chapitre;
use Labstag\Handler\ChapitrePublishingHandler;
use Labstag\Lib\ApiControllerLib;
use Labstag\Repository\ChapitreRepository;
use Psr\Log\LoggerInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\RouterInterface;

class ChapitreApi extends ApiControllerLib
{

    /**
     * @var ChapitrePublishingHandler
     */
    protected $publishingHandler;

    public function __construct(
        ChapitrePublishingHandler $handler,
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

    public function __invoke(Chapitre $data): Chapitre
    {
        $this->publishingHandler->handle($data);

        return $data;
    }

    /**
     * @Route("/api/chapitres/trash", name="api_chapitretrash")
     */
    public function trash(ChapitreRepository $repository): Response
    {
        return $this->trashAction($repository);
    }

    /**
     * @Route("/api/chapitres/trash", name="api_chapitretrashdelete", methods={"DELETE"})
     */
    public function delete(ChapitreRepository $repository): JsonResponse
    {
        return $this->deleteAction($repository);
    }

    /**
     * @Route("/api/chapitres/restore", name="api_chapitrerestore", methods={"POST"})
     */
    public function restore(ChapitreRepository $repository): JsonResponse
    {
        return $this->restoreAction($repository);
    }

    /**
     * @Route("/api/chapitres/empty", name="api_chapitreempty", methods={"POST"})
     */
    public function vider(ChapitreRepository $repository): JsonResponse
    {
        return $this->emptyAction($repository);
    }
}
