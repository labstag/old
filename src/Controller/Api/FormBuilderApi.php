<?php

namespace Labstag\Controller\Api;

use Knp\Component\Pager\PaginatorInterface;
use Labstag\Entity\Formbuilder;
use Labstag\Handler\FormBuilderPublishingHandler;
use Labstag\Lib\ApiControllerLib;
use Labstag\Repository\FormbuilderRepository;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\RouterInterface;

class FormBuilderApi extends ApiControllerLib
{
    public function __construct(
        FormBuilderPublishingHandler $formbuilderPublishingHandler,
        ContainerInterface $container,
        PaginatorInterface $paginator,
        RequestStack $requestStack,
        RouterInterface $router
    )
    {
        $this->formbuilderPublishingHandler = $formbuilderPublishingHandler;
    }

    public function __invoke(Formbuilder $data): Formbuilder
    {
        $this->formbuilderPublishingHandler->handle($data);

        return $data;
    }

    /**
     * @Route("/api/formbuilders/trash", name="api_formbuildertrash")
     */
    public function trash(FormbuilderRepository $repository)
    {
        return $this->trashAction($repository);
    }

    /**
     * @Route("/api/formbuilders/trash", name="api_formbuildertrashdelete", methods={"DELETE"})
     */
    public function delete(FormbuilderRepository $repository)
    {
        return $this->deleteAction($repository);
    }

    /**
     * @Route("/api/formbuilders/restore", name="api_formbuilderrestore", methods={"POST"})
     */
    public function restore(FormbuilderRepository $repository)
    {
        return $this->restoreAction($repository);
    }

    /**
     * @Route("/api/formbuilders/empty", name="api_formbuilderempty", methods={"POST"})
     */
    public function vider(FormbuilderRepository $repository)
    {
        return $this->emptyAction($repository);
    }
}
