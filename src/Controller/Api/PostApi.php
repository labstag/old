<?php

namespace Labstag\Controller\Api;

use Labstag\Lib\ApiControllerLib;
use Labstag\Repository\PostRepository;
use Symfony\Component\Routing\Annotation\Route;

class PostApi extends ApiControllerLib
{
    /**
     * @Route("/api/posts/trash.{_format}", name="api_posttrash")
     *
     * @param string $_format
     */
    public function trash(PostRepository $repository, $_format)
    {
        return $this->trashAction($repository, $_format);
    }

    /**
     * @Route("/api/posts/trash.{_format}", name="api_posttrashdelete", methods={"DELETE"})
     *
     * @param string $_format
     */
    public function delete(PostRepository $repository, $_format)
    {
        return $this->deleteAction($repository, $_format);
    }

    /**
     * @Route("/api/posts/restore.{_format}", name="api_postrestore", methods={"POST"})
     *
     * @param string $_format
     */
    public function restore(PostRepository $repository, $_format)
    {
        return $this->restoreAction($repository, $_format);
    }

    /**
     * @Route("/api/posts/empty.{_format}", name="api_postempty", methods={"POST"})
     *
     * @param string $_format
     */
    public function empty(PostRepository $repository, $_format)
    {
        return $this->emptyAction($repository, $_format);
    }
}
