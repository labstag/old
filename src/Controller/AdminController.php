<?php

namespace Labstag\Controller;

use Labstag\Form\Admin\ProfilType;
use Labstag\Lib\AdminControllerLib;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;

/**
 * @Route("/admin")
 */
class AdminController extends AdminControllerLib
{
    /**
     * @Route("/", name="admin_dashboard")
     */
    public function index(): Response
    {
        return $this->twig(
            'admin/index.html.twig',
            ['title' => 'Dashboard']
        );
    }

    /**
     * @Route("/profil", name="adminprofil_index")
     */
    public function profil(Request $request, Security $security): Response
    {
        $user = $security->getUser();
        $form = $this->createForm(
            ProfilType::class,
            $user,
            [
                'action' => $this->generateUrl('adminprofil_list'),
                'method' => 'POST',
            ]
        );
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $this->addFlash('success', 'Profil sauvegardé');
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('adminprofil_list');
        }

        return $this->twig(
            'admin/crud/form.html.twig',
            [
                'entity'  => $user,
                'title'   => 'Profil',
                'btnSave' => true,
                'form'    => $form->createView(),
            ]
        );
    }
}
