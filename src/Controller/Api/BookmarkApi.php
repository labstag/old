<?php

namespace Labstag\Controller\Api;

use Labstag\Lib\ApiControllerLib;
use Labstag\Repository\BookmarkRepository;
use Symfony\Component\Routing\Annotation\Route;

class BookmarkApi extends ApiControllerLib
{
    /**
     * @Route("/api/bookmark/trash.{_format}", name="api_bookmarktrash")
     *
     * @param string $format
     */
    public function trash(BookmarkRepository $repository, $format)
    {
        return $this->trashAction($repository, $format);
    }

    /**
     * @Route("/api/bookmark/trash.{_format}", name="api_bookmarktrashdelete", methods={"DELETE"})
     *
     * @param string $format
     */
    public function delete(BookmarkRepository $repository, $format)
    {
        return $this->deleteAction($repository, $format);
    }

    /**
     * @Route("/api/bookmark/restore.{_format}", name="api_bookmarkrestore", methods={"POST"})
     *
     * @param string $format
     */
    public function restore(BookmarkRepository $repository, $format)
    {
        return $this->restoreAction($repository, $format);
    }

    /**
     * @Route("/api/bookmark/empty.{_format}", name="api_bookmarkempty", methods={"POST"})
     *
     * @param string $format
     */
    public function vider(BookmarkRepository $repository, $format)
    {
        return $this->emptyAction($repository, $format);
    }
}
