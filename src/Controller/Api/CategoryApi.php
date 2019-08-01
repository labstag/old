<?php

namespace Labstag\Controller\Api;

use Labstag\Lib\ApiControllerLib;
use Labstag\Repository\CategoryRepository;
use Symfony\Component\Routing\Annotation\Route;

class CategoryApi extends ApiControllerLib
{
    /**
     * @Route("/api/categories/trash.{_format}", name="api_categorytrash")
     *
     * @param string $format
     */
    public function trash(CategoryRepository $repository, $format)
    {
        return $this->trashAction($repository, $format);
    }

    /**
     * @Route("/api/categories/trash.{_format}", name="api_categorytrashdelete", methods={"DELETE"})
     *
     * @param string $format
     */
    public function delete(CategoryRepository $repository, $format)
    {
        return $this->deleteAction($repository, $format);
    }

    /**
     * @Route("/api/categories/restore.{_format}", name="api_categoryrestore", methods={"POST"})
     *
     * @param string $format
     */
    public function restore(CategoryRepository $repository, $format)
    {
        return $this->restoreAction($repository, $format);
    }

    /**
     * @Route("/api/categories/empty.{_format}", name="api_categoryempty", methods={"POST"})
     *
     * @param string $format
     */
    public function vider(CategoryRepository $repository, $format)
    {
        return $this->emptyAction($repository, $format);
    }
}
