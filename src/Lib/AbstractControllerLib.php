<?php

namespace App\Lib;

use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;


abstract class AbstractControllerLib extends AbstractController
{
    /**
     *
     * @var Request
     */
    private $request;

    /**
     * @var PaginatorInterface
     */
    private $paginator;

    /**
     * Init controller.
     *
     * @param ContainerInterface $container container
     */
    public function __construct(ContainerInterface $container)
    {
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
     *
     * @return Response
     */
    public function twig(
        $view, array $parameters = [], Response $response = null
    ) {
        $this->addParamViewsSite($parameters);
        $render = $this->render($view, $parameters, $response);

        return $render;
    }

    /**
     * Get generate manifest by webpack.
     *
     * @return void
     */
    private function addManifest(&$parameters): void
    {
        $file     = 'assets/manifest.json';
        $manifest = [];
        if (is_file($file)) {
            $manifest = json_decode(file_get_contents($file), true);
        }

        $parameters['manifest'] = $manifest;
    }

    /**
     * Add param to twig.
     *
     * @param array $parameters array
     *
     * @return void
     */
    protected function addParamViewsSite(array &$parameters): void
    {
        $this->addManifest($parameters);
    }

    protected function paginator($query)
    {
        $pagination = $this->paginator->paginate(
            $query, /* query NOT result */
            $this->request->query->getInt('page', 1), /*page number*/
            10 /*limit per page*/
        );
        $pagination->setTemplate('paginator/pagination.html.twig');
        $pagination->setSortableTemplate('paginator/sortable.html.twig');
        $pagination->setFiltrationTemplate('paginator/filtration.html.twig');

        return $pagination;
    }
}
