<?php

namespace Labstag\Controller\Admin;

use Labstag\Lib\AdminAbstractControllerLib;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin/workflow")
 */
class WorkflowAdmin extends AdminAbstractControllerLib
{
    /**
     * @Route("/", name="adminworkflow_index")
     */
    public function index(Request $request): Response
    {
        return $this->twig(
            'admin/workflow.html.twig',
            ['title' => 'workflow']
        );
    }
}
