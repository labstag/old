<?php

namespace Labstag\Controller\Api;

use Labstag\Lib\ApiControllerLib;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Labstag\Repository\ChapitreRepository;

class ChapitreApi extends ApiControllerLib
{
    /**
     * @Route("/api/chapitres/trash.{_format}", name="api_chapitretrash")
     */
    public function trash(ChapitreRepository $repository, $_format)
    {
        return $this->trashAction($repository, $_format);
    }

    /**
     * @Route("/api/chapitres/trash.{_format}", name="api_chapitretrashdelete", methods={"DELETE"})
     */
    public function delete(ChapitreRepository $repository, $_format)
    {
        return $this->deleteAction($repository, $_format);
    }

    /**
     * @Route("/api/chapitres/restore.{_format}", name="api_chapitrerestore", methods={"POST"})
     */
    public function restore(ChapitreRepository $repository, $_format)
    {
        return $this->restoreAction($repository, $_format);
    }

    /**
     * @Route("/api/chapitres/empty.{_format}", name="api_chapitreempty", methods={"POST"})
     */
    public function empty(ChapitreRepository $repository, $_format)
    {
        return $this->emptyAction($repository, $_format);
    }
}
