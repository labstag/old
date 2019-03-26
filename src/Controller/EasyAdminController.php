<?php

namespace App\Controller;

use EasyCorp\Bundle\EasyAdminBundle\Controller\AdminControllerTrait;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AdminController;

/**
 * @Route("/backend")
 */
class EasyAdminController extends AdminController
{
    use AdminControllerTrait;

    /**
     * @Route("/dashboard", name="easyadmin_dashboard")
     */
    public function dashboard()
    {
        return $this->render(
            'easyadmin/dashboard.html.twig'
        );
    }
}
