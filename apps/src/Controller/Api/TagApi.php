<?php

namespace Labstag\Controller\Api;

use Knp\Component\Pager\PaginatorInterface;
use Labstag\Entity\Tag;
use Labstag\Handler\TagPublishingHandler;
use Labstag\Lib\ApiControllerLib;
use Labstag\Repository\TagRepository;
use Psr\Log\LoggerInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\RouterInterface;

class TagApi extends ApiControllerLib
{

    /**
     * @var TagPublishingHandler
     */
    protected $publishingHandler;

    public function __construct(
        TagPublishingHandler $handler,
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

    public function __invoke(Tag $data): Tag
    {
        $this->publishingHandler->handle($data);

        return $data;
    }

    /**
     * @Route("/api/tags/trash", name="api_tagstrash")
     */
    public function trash(TagRepository $repository): Response
    {
        return $this->trashAction($repository);
    }

    /**
     * @Route("/api/tags/trash", name="api_tagstrashdelete", methods={"DELETE"})
     */
    public function delete(TagRepository $repository): JsonResponse
    {
        return $this->deleteAction($repository);
    }

    /**
     * @Route("/api/tags/restore", name="api_tagsrestore", methods={"Tag"})
     */
    public function restore(TagRepository $repository): JsonResponse
    {
        return $this->restoreAction($repository);
    }

    /**
     * @Route("/api/tags/empty", name="api_tagsempty", methods={"Tag"})
     */
    public function vider(TagRepository $repository): JsonResponse
    {
        return $this->emptyAction($repository);
    }
}
