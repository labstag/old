<?php

namespace App\Controller;

use App\Lib\AbstractControllerLib;
use Symfony\Component\Routing\Annotation\Route;
use App\Controller\Front\PostTrait;

class FrontController extends AbstractControllerLib
{
    use PostTrait;
    /**
     * @Route("/", name="front")
     */
    public function index()
    {
        return $this->redirect(
            $this->generateUrl('posts_list'),
            301
        );
    }
}
