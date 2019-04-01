<?php

namespace App\Controller;

use Symfony\Component\Routing\Annotation\Route;
use App\Lib\AbstractControllerLib;

/**
 * @Route("/admin")
 */
class AdminController extends AbstractControllerLib
{
    /**
     * @Route("/", name="admin")
     */
    public function index()
    {
        return $this->render(
            'admin/index.html.twig',
            array(
                'controller_name' => 'AdminController',
            )
        );
    }
}
