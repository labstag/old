<?php

namespace App\Controller;

use Symfony\Component\Routing\Annotation\Route;
use App\Controller\Admin\PostTrait;
use App\Lib\AbstractControllerLib;

/**
 * @Route("/admin")
 */
class AdminController extends AbstractControllerLib
{
    use PostTrait;
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
