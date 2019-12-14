<?php

namespace Labstag\Controller\Api;

use Knp\Component\Pager\PaginatorInterface;
use Labstag\Entity\Configuration;
use Labstag\Handler\ConfigurationPublishingHandler;
use Labstag\Lib\ApiControllerLib;
use Labstag\Repository\ConfigurationRepository;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\RouterInterface;

class ConfigurationApi extends ApiControllerLib
{
    public function __construct(
        ConfigurationPublishingHandler $handler,
        ContainerInterface $container,
        PaginatorInterface $paginator,
        RequestStack $requestStack,
        RouterInterface $router
    )
    {
        parent::__construct($container, $paginator, $requestStack, $router);
        $this->configurationPublishingHandler = $handler;
    }

    public function __invoke(Configuration $data): Configuration
    {
        $this->configurationPublishingHandler->handle($data);

        return $data;
    }

    /**
     * @Route("/api/configurations/trash", name="api_configurationtrash")
     */
    public function trash(ConfigurationRepository $repository)
    {
        return $this->trashAction($repository);
    }

    /**
     * @Route("/api/configurations/trash", name="api_configurationtrashdelete", methods={"DELETE"})
     */
    public function delete(ConfigurationRepository $repository)
    {
        return $this->deleteAction($repository);
    }

    /**
     * @Route("/api/configurations/restore", name="api_configurationrestore", methods={"POST"})
     */
    public function restore(ConfigurationRepository $repository)
    {
        return $this->restoreAction($repository);
    }

    /**
     * @Route("/api/configurations/empty", name="api_configurationempty", methods={"POST"})
     */
    public function vider(ConfigurationRepository $repository)
    {
        return $this->emptyAction($repository);
    }
}
