<?php

namespace Labstag\Controller\Api;

use Labstag\Entity\Post;
use Labstag\Lib\ApiControllerLib;
use Labstag\Repository\PostRepository;
use Labstag\Handler\PostPublishingHandler;
use Symfony\Component\Routing\Annotation\Route;

class PostApi extends ApiControllerLib
{

    public function __construct(PostPublishingHandler $postPublishingHandler)
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
     *
     * @param string $_format
     */
    public function trash(PostRepository $repository)
    {
        return $this->trashAction($repository);
    }

    /**
     * @Route("/api/posts/trash", name="api_posttrashdelete", methods={"DELETE"})
     *
     * @param string $_format
     */
    public function delete(PostRepository $repository)
    {
        return $this->deleteAction($repository);
    }

    /**
     * @Route("/api/posts/restore", name="api_postrestore", methods={"POST"})
     *
     * @param string $_format
     */
    public function restore(PostRepository $repository)
    {
        return $this->restoreAction($repository);
    }

    /**
     * @Route("/api/posts/empty", name="api_postempty", methods={"POST"})
     *
     * @param string $_format
     */
    public function vider(PostRepository $repository)
    {
        return $this->emptyAction($repository);
    }
}
