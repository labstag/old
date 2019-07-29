<?php

namespace Labstag\Controller\Api;

use Labstag\Lib\ApiControllerLib;
use Labstag\Repository\ChapitreRepository;
use Symfony\Component\Routing\Annotation\Route;

class ChapitreApi extends ApiControllerLib
{
    /**
     * @Route("/api/chapitres/trash.{_format}", name="api_chapitretrash")
     *
     * @param string $_format
     */
    public function trash(ChapitreRepository $repository, $_format)
    {
        return $this->trashAction($repository, $_format);
    }

    /**
     * @Route("/api/chapitres/trash.{_format}", name="api_chapitretrashdelete", methods={"DELETE"})
     *
     * @param string $_format
     */
    public function delete(ChapitreRepository $repository, $_format)
    {
        return $this->deleteAction($repository, $_format);
    }

    /**
     * @Route("/api/chapitres/restore.{_format}", name="api_chapitrerestore", methods={"POST"})
     *
     * @param string $_format
     */
    public function restore(ChapitreRepository $repository, $_format)
    {
        return $this->restoreAction($repository, $_format);
    }

    /**
     * @Route("/api/chapitres/empty.{_format}", name="api_chapitreempty", methods={"POST"})
     *
     * @param string $_format
     */
    public function empty(ChapitreRepository $repository, $_format)
    {
        return $this->emptyAction($repository, $_format);
    }
}
