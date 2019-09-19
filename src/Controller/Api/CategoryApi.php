<?php

namespace Labstag\Controller\Api;

use Labstag\Entity\Category;
use Labstag\Lib\ApiControllerLib;
use Labstag\Handler\CategoryPublishingHandler;
use Labstag\Repository\CategoryRepository;
use Symfony\Component\Routing\Annotation\Route;

class CategoryApi extends ApiControllerLib
{

    public function __construct(CategoryPublishingHandler $categoryPublishingHandler)
    {
        $this->categoryPublishingHandler = $categoryPublishingHandler;
    }

    public function __invoke(Category $data): Category
    {
        $this->categoryPublishingHandler->handle($data);

        return $data;
    }

    /**
     * @Route("/api/categories/trash", name="api_categorytrash")
     *
     * @param string $_format
     */
    public function trash(CategoryRepository $repository)
    {
        return $this->trashAction($repository);
    }

    /**
     * @Route("/api/categories/trash", name="api_categorytrashdelete", methods={"DELETE"})
     *
     * @param string $_format
     */
    public function delete(CategoryRepository $repository)
    {
        return $this->deleteAction($repository);
    }

    /**
     * @Route("/api/categories/restore", name="api_categoryrestore", methods={"POST"})
     *
     * @param string $_format
     */
    public function restore(CategoryRepository $repository)
    {
        return $this->restoreAction($repository);
    }

    /**
     * @Route("/api/categories/empty", name="api_categoryempty", methods={"POST"})
     *
     * @param string $_format
     */
    public function vider(CategoryRepository $repository)
    {
        return $this->emptyAction($repository);
    }
}
