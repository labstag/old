<?php

namespace App\Controller;

use EasyCorp\Bundle\EasyAdminBundle\Controller\AdminControllerTrait;
use Faker\Factory;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * @Route("/backend")
 */
class EasyAdminController extends AbstractController
{
    use AdminControllerTrait;

    /**
     * @Route("/dashboard", name="easyadmin_dashboard")
     */
    public function dashboard()
    {
        $faker = Factory::create('fr_FR');

        return $this->render(
            'easyadmin/dashboard.html.twig'
        );
    }
}
