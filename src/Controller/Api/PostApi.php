<?php

namespace Labstag\Controller\Api;

use Knp\Component\Pager\PaginatorInterface;
use Labstag\Entity\Post;
use Labstag\Handler\PostPublishingHandler;
use Labstag\Lib\ApiControllerLib;
use Labstag\Repository\PostRepository;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\RouterInterface;

class PostApi extends ApiControllerLib
{
    public function __construct(
        PostPublishingHandler $postPublishingHandler,
        ContainerInterface $container,
        PaginatorInterface $paginator,
        RequestStack $requestStack,
        RouterInterface $router
    )
    {
        $this->postPublishingHandler = $postPublishingHandler;
    }

    public function __invoke(Post $data): Post
    {
        $this->postPublishingHandler->handle($data);

        return $data;
    }

    /**
     * @Route("/api/posts/trash", name="api_posttrash")
     */
    public function trash(PostRepository $repository)
    {
        return $this->trashAction($repository);
    }

    /**
     * @Route("/api/posts/trash", name="api_posttrashdelete", methods={"DELETE"})
     */
    public function delete(PostRepository $repository)
    {
        return $this->deleteAction($repository);
    }

    /**
     * @Route("/api/posts/restore", name="api_postrestore", methods={"POST"})
     */
    public function restore(PostRepository $repository)
    {
        return $this->restoreAction($repository);
    }

    /**
     * @Route("/api/posts/empty", name="api_postempty", methods={"POST"})
     */
    public function vider(PostRepository $repository)
    {
        return $this->emptyAction($repository);
    }
}
