<?php

namespace App\Controller;

use Symfony\Component\Routing\Annotation\Route;
use App\Lib\AbstractControllerLib;
use Symfony\Component\HttpFoundation\Response;

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
            [
                'controller_name' => 'AdminController',
            ]
        );
    }
}
