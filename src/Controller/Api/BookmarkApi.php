<?php

namespace Labstag\Controller\Api;

use Knp\Component\Pager\PaginatorInterface;
use Labstag\Entity\Bookmark;
use Labstag\Handler\BookmarkPublishingHandler;
use Labstag\Lib\ApiControllerLib;
use Labstag\Repository\BookmarkRepository;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\RouterInterface;

class BookmarkApi extends ApiControllerLib
{

    /**
     * @var BookmarkPublishingHandler
     */
    protected $publishingHandler;

    public function __construct(
        BookmarkPublishingHandler $handler,
        ContainerInterface $container,
        PaginatorInterface $paginator,
        RequestStack $requestStack,
        RouterInterface $router
    )
    {
        parent::__construct($container, $paginator, $requestStack, $router);
        $this->publishingHandler = $handler;
    }

    public function __invoke(Bookmark $data): Bookmark
    {
        $this->publishingHandler->handle($data);

        return $data;
    }

    /**
     * @Route("/api/bookmarks/trash", name="api_bookmarktrash")
     */
    public function trashBookmark(BookmarkRepository $repository): Response
    {
        return $this->trashAction($repository);
    }

    /**
     * @Route("/api/bookmarks/trash", name="api_bookmarktrashdelete", methods={"DELETE"})
     */
    public function deleteBookmark(BookmarkRepository $repository): JsonResponse
    {
        return $this->deleteAction($repository);
    }

    /**
     * @Route("/api/bookmarks/restore", name="api_bookmarkrestore", methods={"POST"})
     */
    public function restoreBookmark(BookmarkRepository $repository): JsonResponse
    {
        return $this->restoreAction($repository);
    }

    /**
     * @Route("/api/bookmarks/empty", name="api_bookmarkempty", methods={"POST"})
     */
    public function viderBookmark(BookmarkRepository $repository): JsonResponse
    {
        return $this->emptyAction($repository);
    }
}
