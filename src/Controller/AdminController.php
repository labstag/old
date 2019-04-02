<?php

namespace App\Controller;

use App\Lib\AbstractControllerLib;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin")
 */
class AdminController extends AbstractControllerLib
{
    /**
     * @Route("/", name="admin")
     */
    public function index(): Response
    {
        return $this->render(
            'admin/index.html.twig',
            ['controller_name' => 'AdminController']
        );
    }
}
