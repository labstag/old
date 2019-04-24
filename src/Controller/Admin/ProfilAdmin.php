<?php

namespace Labstag\Controller\Admin;

use Labstag\Lib\AdminAbstractControllerLib;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin/profil")
 */
class ProfilAdmin extends AdminAbstractControllerLib
{
    /**
     * @Route("/", name="adminprofil_index")
     */
    public function index(Request $request): Response
    {
        $auth_checker = $this->get('security.authorization_checker');
        return $this->json($auth_checker->isGranted('IS_AUTHENTICATED_FULLY'));
    }
}
