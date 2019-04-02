<?php

namespace App\Controller;

use EasyCorp\Bundle\EasyAdminBundle\Controller\AdminControllerTrait;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AdminController;
use Symfony\Component\HttpFoundation\Response;

/**
 * @Route("/backend")
 */
class EasyAdminController extends AdminController
{
    use AdminControllerTrait;

    /**
     * @Route("/dashboard", name="easyadmin_dashboard")
     */
    public function dashboard(): Response
    {
        return $this->render('easyadmin/dashboard.html.twig');
    }
}
