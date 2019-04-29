<?php

namespace Labstag\Controller;

use Labstag\Controller\Front\PostTrait;
use Labstag\Lib\ControllerLib;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Routing\Annotation\Route;

class FrontController extends ControllerLib
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
}
