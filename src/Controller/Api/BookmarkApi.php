<?php

namespace Labstag\Controller\Api;

use Knp\Component\Pager\PaginatorInterface;
use Labstag\Entity\Bookmark;
use Labstag\Handler\BookmarkPublishingHandler;
use Labstag\Lib\ApiControllerLib;
use Labstag\Repository\BookmarkRepository;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\RouterInterface;

class BookmarkApi extends ApiControllerLib
{
    public function __construct(
        BookmarkPublishingHandler $bookmarkPublishingHandler,
        ContainerInterface $container,
        PaginatorInterface $paginator,
        RequestStack $requestStack,
        RouterInterface $router
    )
    {
        parent::__construct($container, $paginator, $requestStack, $router);
        $this->bookmarkPublishingHandler = $bookmarkPublishingHandler;
    }

    public function __invoke(Bookmark $data): Bookmark
    {
        $this->bookmarkPublishingHandler->handle($data);

        return $data;
    }

    /**
     * @Route("/api/bookmarks/trash", name="api_bookmarktrash")
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
