<?php

namespace Labstag\Controller\Admin;

use Labstag\Form\Admin\ParamType;
use Labstag\Lib\AdminAbstractControllerLib;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin/param")
 */
class ParamAdmin extends AdminAbstractControllerLib
{
    /**
     * @Route("/", name="adminparam_index")
     */
    public function index(Request $request): Response
    {

        $data = [
            'oauth' => []
        ];

        $form = $this->createForm(ParamType::class, $data);
        return $this->twig(
            'admin/param.html.twig',
            [
                'title' => 'Paramètres',
                'form'  => $form->createView(),
            ]
        );
    }
}
