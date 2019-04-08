<?php

namespace Labstag\Controller;

use Labstag\Controller\Front\PostTrait;
use Labstag\Lib\AbstractControllerLib;
use League\Glide\Responses\SymfonyResponseFactory;
use League\Glide\ServerFactory;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Routing\Annotation\Route;

class FrontController extends AbstractControllerLib
{
    use PostTrait;

    /**
     * @Route("/", name="front")
     */
    public function index(): RedirectResponse
    {
        return $this->redirect(
            $this->generateUrl('posts_list'),
            301
        );
    }

    /**
     * @Route("/image/{filterName}/{imageName}", name="glide")
     */
    public function glide(string $filterName, string $imageName)
    {
        $parameters = $this->getParameter('media_filters');
        $server     = ServerFactory::create(
            [
                'response' => new SymfonyResponseFactory(),
                'source'   => 'file',
                'cache'    => 'file/tmp',
            ]
        );
        $server->outputImage($imageName, $parameters[$filterName]);
    }
}
