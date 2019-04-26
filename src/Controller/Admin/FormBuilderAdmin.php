<?php

namespace Labstag\Controller\Admin;

use Labstag\Lib\AdminAbstractControllerLib;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin/formbuilder")
 */
class FormBuilderAdmin extends AdminAbstractControllerLib
{
    /**
     * @Route("/", name="adminformbuilder_index")
     */
    public function index(Request $request): Response
    {
        return $this->twig(
            'admin/formbuilder.html.twig',
            ['title' => 'FormBuilder']
        );
    }
}
