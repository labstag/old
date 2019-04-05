<?php

namespace Labstag\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Labstag\Lib\AdminAbstractControllerLib;

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
