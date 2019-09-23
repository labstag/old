<?php

namespace Labstag\Controller\Api;

use Knp\Component\Pager\PaginatorInterface;
use Labstag\Entity\Chapitre;
use Labstag\Handler\ChapitrePublishingHandler;
use Labstag\Lib\ApiControllerLib;
use Labstag\Repository\ChapitreRepository;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\RouterInterface;

class ChapitreApi extends ApiControllerLib
{
    public function __construct(
        ChapitrePublishingHandler $chapitrePublishingHandler,
        ContainerInterface $container,
        PaginatorInterface $paginator,
        RequestStack $requestStack,
        RouterInterface $router
    )
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
     */
    public function trash(ChapitreRepository $repository)
    {
        return $this->trashAction($repository);
    }

    /**
     * @Route("/api/chapitres/trash", name="api_chapitretrashdelete", methods={"DELETE"})
     */
    public function delete(ChapitreRepository $repository)
    {
        return $this->deleteAction($repository);
    }

    /**
     * @Route("/api/chapitres/restore", name="api_chapitrerestore", methods={"POST"})
     */
    public function restore(ChapitreRepository $repository)
    {
        return $this->restoreAction($repository);
    }

    /**
     * @Route("/api/chapitres/empty", name="api_chapitreempty", methods={"POST"})
     */
    public function vider(ChapitreRepository $repository)
    {
        return $this->emptyAction($repository);
    }
}
