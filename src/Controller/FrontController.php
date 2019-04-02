<?php

namespace App\Controller;

use App\Controller\Front\PostTrait;
use App\Lib\AbstractControllerLib;
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
}
