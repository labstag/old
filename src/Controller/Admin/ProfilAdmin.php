<?php

namespace Labstag\Controller\Admin;

use Labstag\Lib\AdminControllerLib;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin/profil")
 */
class ProfilAdmin extends AdminControllerLib
{
    /**
     * @Route("/", name="adminprofil_index")
     */
    public function index(Request $request): Response
    {
        return $this->twig(
            'admin/profil.html.twig',
            ['title' => 'Profil']
        );
    }
}
