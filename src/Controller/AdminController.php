<?php

namespace Labstag\Controller;

use Labstag\Lib\AdminControllerLib;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin")
 */
class AdminController extends AdminControllerLib
{
    /**
     * @Route("/", name="admin_dashboard")
     */
    public function index(): Response
    {
        return $this->twig(
            'admin/index.html.twig'
        );
    }
}
