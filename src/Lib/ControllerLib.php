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

    private $router;

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
        $this->router       = $container->get('router');
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

    protected function setConfigurationParam()
    {
        if (isset($this->paramViews['config'])) {
            return;
        }

        $configurationRepository = $this->container->get(ConfigurationRepository::class);
        $data                    = $configurationRepository->findAll();
        $config                  = [];

        foreach ($data as $row) {
            $key   = $row->getName();
            $value = $row->getValue();

            $config[$key] = $value;
        }

        if(isset($config['oauth'])) {
            $oauth = array();
            foreach ($config['oauth'] as $data) {
                if ($data['activate'] == 1) {
                    $type         = $data['type'];
                    $oauth[$type] = $data;
                }
            }

            $this->paramViews['oauth_activated'] = $oauth;
        }

        $this->paramViews['config'] = $config;
    }
}
