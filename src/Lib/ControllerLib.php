<?php

namespace Labstag\Lib;

use Knp\Component\Pager\PaginatorInterface;
use Labstag\Repository\ConfigurationRepository;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

abstract class ControllerLib extends Controller
{

    /**
     * @var Request
     */
    protected $request;

    /**
     * @var PaginatorInterface
     */
    private $paginator;

    /**
     * Parameters for twig.
     *
     * @var array
     */
    private $parameters;

    /**
     * Init controller.
     *
     * @param ContainerInterface $container container
     */
    public function __construct(ContainerInterface $container)
    {
        $this->paramViews   = [];
        $this->container    = $container;
        $this->paginator    = $container->get('knp_paginator');
        $this->requestStack = $container->get('request_stack');
        $this->request      = $this->requestStack->getCurrentRequest();
    }

    /**
     * Show.
     *
     * @param string    $view       template
     * @param array     $parameters data
     * @param ?Response $response   ??
     */
    public function twig(
        string $view,
        array $parameters = [],
        Response $response = null
    ): Response
    {
        $this->addParamViewsSite($parameters);

        return parent::render($view, $this->paramViews, $response);
    }

    /**
     * Add param to twig.
     */
    protected function addParamViewsSite(array $parameters = []): void
    {
        $this->setConfigurationParam();
        $this->paramViews = array_merge($parameters, $this->paramViews);
    }

    protected function paginator($query)
    {
        $pagination = $this->paginator->paginate(
            $query,
            // query NOT result
            $this->request->query->getInt('page', 1),
            // page number
            10
            // limit per page
        );
        $pagination->setTemplate('paginator/pagination.html.twig');
        $pagination->setSortableTemplate('paginator/sortable.html.twig');
        $pagination->setFiltrationTemplate('paginator/filtration.html.twig');

        $this->paramViews['pagination'] = $pagination;
    }

    private function setConfigurationParam()
    {
        $configurationRepository = $this->container->get(ConfigurationRepository::class);
        $data                    = $configurationRepository->GetDataArray();
        $config                  = [];

        foreach ($data as $row) {
            $key          = $row['c_name'];
            $value        = $row['c_value'];
            $config[$key] = $value;
        }

        dump($config);
        $this->paramViews['config'] = $config;
    }
}
