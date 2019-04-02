<?php

namespace App\Controller;

use App\Lib\AbstractControllerLib;
use App\Controller\Front\PostTrait;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\RedirectResponse;

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
