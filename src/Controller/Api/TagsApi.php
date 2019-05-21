<?php

namespace Labstag\Controller\Api;

use Labstag\Lib\ApiControllerLib;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Labstag\Repository\TagsRepository;

class TagsApi extends ApiControllerLib
{
    /**
     * @Route("/api/tags/trash.{_format}", name="api_tagstrash")
     */
    public function trash(TagsRepository $repository, $_format)
    {
        return $this->trashAction($repository, $_format);
    }

    /**
     * @Route("/api/tags/trash.{_format}", name="api_tagstrashdelete", methods={"DELETE"})
     */
    public function delete(TagsRepository $repository, $_format)
    {
        return $this->deleteAction($repository, $_format);
    }

    /**
     * @Route("/api/tags/restore.{_format}", name="api_tagsrestore", methods={"POST"})
     */
    public function restore(TagsRepository $repository, $_format)
    {
        return $this->restoreAction($repository, $_format);
    }

    /**
     * @Route("/api/tags/empty.{_format}", name="api_tagsempty", methods={"POST"})
     */
    public function empty(TagsRepository $repository, $_format)
    {
        return $this->emptyAction($repository, $_format);
    }
}
