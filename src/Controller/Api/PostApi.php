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
     * @param string $format
     */
    public function trash(PostRepository $repository, $format)
    {
        return $this->trashAction($repository, $format);
    }

    /**
     * @Route("/api/posts/trash.{_format}", name="api_posttrashdelete", methods={"DELETE"})
     *
     * @param string $format
     */
    public function delete(PostRepository $repository, $format)
    {
        return $this->deleteAction($repository, $format);
    }

    /**
     * @Route("/api/posts/restore.{_format}", name="api_postrestore", methods={"POST"})
     *
     * @param string $format
     */
    public function restore(PostRepository $repository, $format)
    {
        return $this->restoreAction($repository, $format);
    }

    /**
     * @Route("/api/posts/empty.{_format}", name="api_postempty", methods={"POST"})
     *
     * @param string $format
     */
    public function vider(PostRepository $repository, $format)
    {
        return $this->emptyAction($repository, $format);
    }
}
