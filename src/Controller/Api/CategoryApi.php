<?php

namespace Labstag\Controller\Api;

use Labstag\Lib\ApiControllerLib;
use Labstag\Repository\CategoryRepository;
use Symfony\Component\Routing\Annotation\Route;

class CategoryApi extends ApiControllerLib
{
    /**
     * @Route("/api/categories/trash.{_format}", name="api_categorytrash")
     */
    public function trash(CategoryRepository $repository, $_format)
    {
        return $this->trashAction($repository, $_format);
    }

    /**
     * @Route("/api/categories/trash.{_format}", name="api_categorytrashdelete", methods={"DELETE"})
     */
    public function delete(CategoryRepository $repository, $_format)
    {
        return $this->deleteAction($repository, $_format);
    }

    /**
     * @Route("/api/categories/restore.{_format}", name="api_categoryrestore", methods={"POST"})
     */
    public function restore(CategoryRepository $repository, $_format)
    {
        return $this->restoreAction($repository, $_format);
    }

    /**
     * @Route("/api/categories/empty.{_format}", name="api_categoryempty", methods={"POST"})
     */
    public function empty(CategoryRepository $repository, $_format)
    {
        return $this->emptyAction($repository, $_format);
    }
}
