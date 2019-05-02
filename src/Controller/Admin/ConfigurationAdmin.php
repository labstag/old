<?php

namespace Labstag\Controller\Admin;

use Labstag\Entity\Configuration;
use Labstag\Form\Admin\ConfigurationType;
use Labstag\Lib\AdminControllerLib;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin/configuration")
 */
class ConfigurationAdmin extends AdminControllerLib
{
    /**
     * @Route("/", name="adminconfiguration_index", methods={"GET"})
     */
    public function index(): Response
    {
        $datatable = [
            'Name'      => [
                'field'    => 'name',
                'sortable' => true,
            ],
            'Value'      => [
                'field'    => 'value',
                'sortable' => true,
            ],
            'CreatedAt' => [
                'field'     => 'createdAt',
                'sortable'  => true,
                'formatter' => 'dateFormatter',
            ],
            'UpdatedAt' => [
                'field'     => 'updatedAt',
                'sortable'  => true,
                'formatter' => 'dateFormatter',
            ],
        ];
        $data      = [
            'title'     => 'Configuration index',
            'datatable' => $datatable,
            'api'       => 'api_configurations_get_collection',
            'new'       => 'adminconfiguration_new',
        ];
        return $this->crudListAction($data);
    }

    /**
     * @Route("/new", name="adminconfiguration_new", methods={"GET", "POST"})
     */
    public function new(Request $request): Response
    {
        return $this->crudNewAction(
            $request,
            [
                'entity'    => new Configuration(),
                'form'      => ConfigurationType::class,
                'url_edit'  => 'adminconfiguration_edit',
                'url_index' => 'adminconfiguration_index',
                'title'     => 'Add new configuration',
            ]
        );
    }

    /**
     * @Route("/edit/{id}", name="adminconfiguration_edit", methods={"GET", "POST"})
     */
    public function edit(
        Request $request,
        Configuration $configuration
    ): Response
    {
        return $this->crudEditAction(
            $request,
            [
                'form'      => ConfigurationType::class,
                'entity'    => $configuration,
                'url_index' => 'adminconfiguration_index',
                'url_edit'  => 'adminconfiguration_edit',
                'title'     => 'Edit configuration',
            ]
        );
    }

    /**
     * @Route("/delete/{id}", name="adminconfiguration_delete", methods={"DELETE"})
     */
    public function delete(
        Request $request,
        Configuration $configuration
    ): Response
    {
        return $this->crudActionDelete($request, $configuration, 'adminconfiguration_index');
    }
}
