<?php

namespace Labstag\Controller\Api;

use Knp\Component\Pager\PaginatorInterface;
use Labstag\Entity\Template;
use Labstag\Handler\TemplatePublishingHandler;
use Labstag\Lib\ApiControllerLib;
use Labstag\Repository\TemplateRepository;
use Psr\Log\LoggerInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\RouterInterface;

class TemplateApi extends ApiControllerLib
{

    /**
     * @var TemplatePublishingHandler
     */
    protected $publishingHandler;

    public function __construct(
        TemplatePublishingHandler $handler,
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

    public function __invoke(Template $data): Template
    {
        $this->publishingHandler->handle($data);

        return $data;
    }

    /**
     * @Route("/api/template/trash", name="api_templatetrash")
     */
    public function trash(TemplateRepository $repository): Response
    {
        return $this->trashAction($repository);
    }

    /**
     * @Route("/api/template/trash", name="api_templatetrashdelete", methods={"DELETE"})
     */
    public function delete(TemplateRepository $repository): JsonResponse
    {
        return $this->deleteAction($repository);
    }

    /**
     * @Route("/api/template/restore", name="api_templaterestore", methods={"POST"})
     */
    public function restore(TemplateRepository $repository): JsonResponse
    {
        return $this->restoreAction($repository);
    }

    /**
     * @Route("/api/template/empty", name="api_templateempty", methods={"POST"})
     */
    public function vider(TemplateRepository $repository): JsonResponse
    {
        return $this->emptyAction($repository);
    }
}
