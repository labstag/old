<?php

namespace Labstag\Controller\Api;

use Knp\Component\Pager\PaginatorInterface;
use Labstag\Entity\Templates;
use Labstag\Handler\TemplatesPublishingHandler;
use Labstag\Lib\ApiControllerLib;
use Labstag\Repository\TemplatesRepository;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\RouterInterface;

class TemplatesApi extends ApiControllerLib
{

    /**
     * @var TemplatesPublishingHandler
     */
    protected $publishingHandler;

    public function __construct(
        TemplatesPublishingHandler $publishingHandler,
        ContainerInterface $container,
        PaginatorInterface $paginator,
        RequestStack $requestStack,
        RouterInterface $router
    )
    {
        parent::__construct($container, $paginator, $requestStack, $router);
        $this->publishingHandler = $publishingHandler;
    }

    public function __invoke(Templates $data): Templates
    {
        $this->publishingHandler->handle($data);

        return $data;
    }

    /**
     * @Route("/api/templates/trash", name="api_templatestrash")
     */
    public function trash(TemplatesRepository $repository)
    {
        return $this->trashAction($repository);
    }

    /**
     * @Route("/api/templates/trash", name="api_templatestrashdelete", methods={"DELETE"})
     */
    public function delete(TemplatesRepository $repository)
    {
        return $this->deleteAction($repository);
    }

    /**
     * @Route("/api/templates/restore", name="api_templatesrestore", methods={"POST"})
     */
    public function restore(TemplatesRepository $repository)
    {
        return $this->restoreAction($repository);
    }

    /**
     * @Route("/api/templates/empty", name="api_templatesempty", methods={"POST"})
     */
    public function vider(TemplatesRepository $repository)
    {
        return $this->emptyAction($repository);
    }
}
