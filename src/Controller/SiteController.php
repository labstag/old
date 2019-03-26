<?php

namespace App\Controller;

use App\Lib\AbstractControllerLib;
use Symfony\Component\Routing\Annotation\Route;

class SiteController extends AbstractControllerLib
{
    /**
     * @Route("/", name="site")
     */
    public function index()
    {
        return $this->redirect(
            $this->generateUrl('posts_list'),
            301
        );
    }
}
