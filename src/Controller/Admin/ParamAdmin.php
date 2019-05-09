<?php

namespace Labstag\Controller\Admin;

use Labstag\Form\Admin\ParamType;
use Labstag\Lib\AdminControllerLib;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin/param")
 */
class ParamAdmin extends AdminControllerLib
{
    /**
     * @Route("/", name="adminparam_list")
     */
    public function list(Request $request): Response
    {
        $data = [
            'oauth' => [],
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
