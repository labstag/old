<?php

namespace App\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AdminController;
use Faker\Factory;

/**
 * @Route("/backend")
 */
class EasyAdminController extends AdminController
{
    /**
     * @Route("/dashboard", name="easyadmin_dashboard")
     *
     * @return void
     */
    public function dashboard()
    {
        $faker = Factory::create('fr_FR');
        return $this->render(
            'easyadmin/dashboard.html.twig'
        );
    }
}
