<?php

namespace Labstag\Controller\Api;

use Labstag\Lib\ApiControllerLib;
use Labstag\Repository\TagsRepository;
use Symfony\Component\Routing\Annotation\Route;

class TagsApi extends ApiControllerLib
{
    /**
     * @Route("/api/tags/trash.{_format}", name="api_tagstrash")
     *
     * @param string $format
     */
    public function trash(TagsRepository $repository, $format)
    {
        return $this->trashAction($repository, $format);
    }

    /**
     * @Route("/api/tags/trash.{_format}", name="api_tagstrashdelete", methods={"DELETE"})
     *
     * @param string $format
     */
    public function delete(TagsRepository $repository, $format)
    {
        return $this->deleteAction($repository, $format);
    }

    /**
     * @Route("/api/tags/restore.{_format}", name="api_tagsrestore", methods={"POST"})
     *
     * @param string $format
     */
    public function restore(TagsRepository $repository, $format)
    {
        return $this->restoreAction($repository, $format);
    }

    /**
     * @Route("/api/tags/empty.{_format}", name="api_tagsempty", methods={"POST"})
     *
     * @param string $format
     */
    public function vider(TagsRepository $repository, $format)
    {
        return $this->emptyAction($repository, $format);
    }
}
