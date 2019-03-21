<?php

namespace App\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AdminController;

/**
 * @Route("/backend")
 */
class EasyAdminController extends AdminController
{
    /**
     * @Route("/", name="easyadmin_dashboard")
     *
     * @return void
     */
    public function dashboard()
    {
        return $this->render(
            'easyadmin/dashboard.html.twig'
        );
    }
}
