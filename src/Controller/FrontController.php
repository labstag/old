<?php

namespace Labstag\Controller;

use Labstag\Lib\ControllerLib;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Routing\Annotation\Route;
use function GuzzleHttp\Psr7\readline;

class FrontController extends ControllerLib
{

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
}
