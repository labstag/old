<?php

namespace Labstag\Controller\Admin;

use Labstag\Entity\Post;
use Labstag\Form\Admin\PostType;
use Labstag\Lib\AdminAbstractControllerLib;
use Labstag\Repository\PostRepository;
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

        $form = $this->createForm(ParamType::class);
        return $this->twig(
            'admin/param.html.twig',
            [
                'title' => 'Paramètres',
                'form'  => $form->createView(),
            ]
        );
    }
}
