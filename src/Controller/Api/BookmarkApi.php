<?php

namespace Labstag\Controller\Api;

use Labstag\Lib\ApiControllerLib;
use Labstag\Repository\BookmarkRepository;
use Symfony\Component\Routing\Annotation\Route;

class BookmarkApi extends ApiControllerLib
{
    /**
     * @Route("/api/bookmarks/trash.{_format}", name="api_bookmarktrash")
     *
     * @param string $_format
     */
    public function trash(BookmarkRepository $repository, $_format)
    {
        return $this->trashAction($repository, $_format);
    }

    /**
     * @Route("/api/bookmarks/trash.{_format}", name="api_bookmarktrashdelete", methods={"DELETE"})
     *
     * @param string $_format
     */
    public function delete(BookmarkRepository $repository, $_format)
    {
        return $this->deleteAction($repository, $_format);
    }

    /**
     * @Route("/api/bookmarks/restore.{_format}", name="api_bookmarkrestore", methods={"POST"})
     *
     * @param string $_format
     */
    public function restore(BookmarkRepository $repository, $_format)
    {
        return $this->restoreAction($repository, $_format);
    }

    /**
     * @Route("/api/bookmarks/empty.{_format}", name="api_bookmarkempty", methods={"POST"})
     *
     * @param string $_format
     */
    public function vider(BookmarkRepository $repository, $_format)
    {
        return $this->emptyAction($repository, $_format);
    }
}
