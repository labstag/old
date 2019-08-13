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
     * @param string $format
     */
    public function trash(ChapitreRepository $repository, $format)
    {
        return $this->trashAction($repository, $format);
    }

    /**
     * @Route("/api/chapitres/trash.{_format}", name="api_chapitretrashdelete", methods={"DELETE"})
     *
     * @param string $format
     */
    public function delete(ChapitreRepository $repository, $format)
    {
        return $this->deleteAction($repository, $format);
    }

    /**
     * @Route("/api/chapitres/restore.{_format}", name="api_chapitrerestore", methods={"POST"})
     *
     * @param string $format
     */
    public function restore(ChapitreRepository $repository, $format)
    {
        return $this->restoreAction($repository, $format);
    }

    /**
     * @Route("/api/chapitres/empty.{_format}", name="api_chapitreempty", methods={"POST"})
     *
     * @param string $format
     */
    public function vider(ChapitreRepository $repository, $format)
    {
        return $this->emptyAction($repository, $format);
    }
}
