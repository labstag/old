<?php

namespace Labstag\Controller\Api;

use Labstag\Entity\Chapitre;
use Labstag\Lib\ApiControllerLib;
use Labstag\Repository\ChapitreRepository;
use Labstag\Handler\ChapitrePublishingHandler;
use Symfony\Component\Routing\Annotation\Route;

class ChapitreApi extends ApiControllerLib
{

    public function __construct(ChapitrePublishingHandler $chapitrePublishingHandler)
    {
        $this->chapitrePublishingHandler = $chapitrePublishingHandler;
    }

    public function __invoke(Chapitre $data): Chapitre
    {
        $this->chapitrePublishingHandler->handle($data);

        return $data;
    }
    
    /**
     * @Route("/api/chapitres/trash", name="api_chapitretrash")
     *
     * @param string $_format
     */
    public function trash(ChapitreRepository $repository)
    {
        return $this->trashAction($repository);
    }

    /**
     * @Route("/api/chapitres/trash", name="api_chapitretrashdelete", methods={"DELETE"})
     *
     * @param string $_format
     */
    public function delete(ChapitreRepository $repository)
    {
        return $this->deleteAction($repository);
    }

    /**
     * @Route("/api/chapitres/restore", name="api_chapitrerestore", methods={"POST"})
     *
     * @param string $_format
     */
    public function restore(ChapitreRepository $repository)
    {
        return $this->restoreAction($repository);
    }

    /**
     * @Route("/api/chapitres/empty", name="api_chapitreempty", methods={"POST"})
     *
     * @param string $_format
     */
    public function vider(ChapitreRepository $repository)
    {
        return $this->emptyAction($repository);
    }
}
