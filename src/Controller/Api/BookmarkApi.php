<?php

namespace Labstag\Controller\Api;

use Labstag\Entity\Bookmark;
use Labstag\Handler\BookmarkPublishingHandler;
use Labstag\Lib\ApiControllerLib;
use Labstag\Repository\BookmarkRepository;
use Symfony\Component\Routing\Annotation\Route;

class BookmarkApi extends ApiControllerLib
{

    public function __construct(BookmarkPublishingHandler $bookmarkPublishingHandler)
    {
        $this->bookmarkPublishingHandler = $bookmarkPublishingHandler;
    }

    public function __invoke(Bookmark $data): Bookmark
    {
        $this->bookmarkPublishingHandler->handle($data);

        return $data;
    }

    /**
     * @Route("/api/bookmarks/trash", name="api_bookmarktrash")
     *
     */
    public function trashBookmark(BookmarkRepository $repository)
    {
        return $this->trashAction($repository);
    }

    /**
     * @Route("/api/bookmarks/trash", name="api_bookmarktrashdelete", methods={"DELETE"})
     */
    public function deleteBookmark(BookmarkRepository $repository)
    {
        return $this->deleteAction($repository);
    }

    /**
     * @Route("/api/bookmarks/restore", name="api_bookmarkrestore", methods={"POST"})
     */
    public function restoreBookmark(BookmarkRepository $repository)
    {
        return $this->restoreAction($repository);
    }

    /**
     * @Route("/api/bookmarks/empty", name="api_bookmarkempty", methods={"POST"})
     */
    public function viderBookmark(BookmarkRepository $repository)
    {
        return $this->emptyAction($repository);
    }
}
