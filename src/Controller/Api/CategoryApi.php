<?php

namespace Labstag\Controller\Api;

use Knp\Component\Pager\PaginatorInterface;
use Labstag\Entity\Category;
use Labstag\Handler\CategoryPublishingHandler;
use Labstag\Lib\ApiControllerLib;
use Labstag\Repository\CategoryRepository;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\RouterInterface;

class CategoryApi extends ApiControllerLib
{
    public function __construct(
        CategoryPublishingHandler $handler,
        ContainerInterface $container,
        PaginatorInterface $paginator,
        RequestStack $requestStack,
        RouterInterface $router
    )
    {
        parent::__construct($container, $paginator, $requestStack, $router);
        $this->categoryPublishingHandler = $handler;
    }

    public function __invoke(Category $data): Category
    {
        $this->categoryPublishingHandler->handle($data);

        return $data;
    }

    /**
     * @Route("/api/categories/trash", name="api_categorytrash")
     */
    public function trash(CategoryRepository $repository)
    {
        return $this->trashAction($repository);
    }

    /**
     * @Route("/api/categories/trash", name="api_categorytrashdelete", methods={"DELETE"})
     */
    public function delete(CategoryRepository $repository)
    {
        return $this->deleteAction($repository);
    }

    /**
     * @Route("/api/categories/restore", name="api_categoryrestore", methods={"POST"})
     */
    public function restore(CategoryRepository $repository)
    {
        return $this->restoreAction($repository);
    }

    /**
     * @Route("/api/categories/empty", name="api_categoryempty", methods={"POST"})
     */
    public function vider(CategoryRepository $repository)
    {
        return $this->emptyAction($repository);
    }
}
