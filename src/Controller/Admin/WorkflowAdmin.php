<?php

namespace Labstag\Controller\Admin;

use Labstag\Lib\AdminControllerLib;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin/workflow")
 */
class WorkflowAdmin extends AdminControllerLib
{
    /**
     * @Route("/", name="adminworkflow_list")
     */
    public function list(Request $request): Response
    {
        return $this->twig(
            'admin/workflow.html.twig',
            ['title' => 'workflow']
        );
    }
}
