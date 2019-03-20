<?php

namespace App\Lib;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\DependencyInjection\ContainerInterface;


abstract class AbstractControllerLib extends AbstractController
{
    /**
     * Init controller.
     *
     * @param ContainerInterface $container container
     */
    public function __construct(ContainerInterface $container)
    { 
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
    )
    {
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
}
