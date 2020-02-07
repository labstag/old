<?php

namespace Labstag\Controller\Api;

use Knp\Component\Pager\PaginatorInterface;
use Labstag\Entity\Formbuilder;
use Labstag\Handler\FormbuilderPublishingHandler;
use Labstag\Lib\ApiControllerLib;
use Labstag\Repository\FormbuilderRepository;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\RouterInterface;

class FormbuilderApi extends ApiControllerLib
{

    /**
     * @var FormbuilderPublishingHandler
     */
    protected $publishingHandler;

    public function __construct(
        FormBuilderPublishingHandler $handler,
        ContainerInterface $container,
        PaginatorInterface $paginator,
        RequestStack $requestStack,
        RouterInterface $router
    )
    {
        parent::__construct($container, $paginator, $requestStack, $router);
        $this->publishingHandler = $handler;
    }

    public function __invoke(Formbuilder $data): Formbuilder
    {
        $this->publishingHandler->handle($data);

        return $data;
    }

    /**
     * @Route("/api/formbuilders/trash", name="api_formbuildertrash")
     */
    public function trash(FormbuilderRepository $repository): Response
    {
        return $this->trashAction($repository);
    }

    /**
     * @Route("/api/formbuilders/trash", name="api_formbuildertrashdelete", methods={"DELETE"})
     */
    public function delete(FormbuilderRepository $repository): JsonResponse
    {
        return $this->deleteAction($repository);
    }

    /**
     * @Route("/api/formbuilders/restore", name="api_formbuilderrestore", methods={"POST"})
     */
    public function restore(FormbuilderRepository $repository): JsonResponse
    {
        return $this->restoreAction($repository);
    }

    /**
     * @Route("/api/formbuilders/empty", name="api_formbuilderempty", methods={"POST"})
     */
    public function vider(FormbuilderRepository $repository): JsonResponse
    {
        return $this->emptyAction($repository);
    }
}
