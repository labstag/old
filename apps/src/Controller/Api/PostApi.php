<?php

namespace Labstag\Controller\Api;

use Knp\Component\Pager\PaginatorInterface;
use Labstag\Entity\Post;
use Labstag\Handler\PostPublishingHandler;
use Labstag\Lib\ApiControllerLib;
use Labstag\Repository\PostRepository;
use Psr\Log\LoggerInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\RouterInterface;

class PostApi extends ApiControllerLib
{

    /**
     * @var PostPublishingHandler
     */
    protected $publishingHandler;

    public function __construct(
        PostPublishingHandler $handler,
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

    public function __invoke(Post $data): Post
    {
        $this->publishingHandler->handle($data);

        return $data;
    }

    /**
     * @Route("/api/posts/trash", name="api_posttrash")
     */
    public function trash(PostRepository $repository): Response
    {
        return $this->trashAction($repository);
    }

    /**
     * @Route("/api/posts/trash", name="api_posttrashdelete", methods={"DELETE"})
     */
    public function delete(PostRepository $repository): JsonResponse
    {
        return $this->deleteAction($repository);
    }

    /**
     * @Route("/api/posts/restore", name="api_postrestore", methods={"POST"})
     */
    public function restore(PostRepository $repository): JsonResponse
    {
        return $this->restoreAction($repository);
    }

    /**
     * @Route("/api/posts/empty", name="api_postempty", methods={"POST"})
     */
    public function vider(PostRepository $repository): JsonResponse
    {
        return $this->emptyAction($repository);
    }
}
