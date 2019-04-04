<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Lib\AdminAbstractControllerLib;

/**
 * @Route("/admin")
 */
class AdminController extends AdminAbstractControllerLib
{
    /**
     * @Route("/", name="admin")
     */
    public function index(): Response
    {
        return $this->twig(
            'admin/index.html.twig'
        );
    }
}
