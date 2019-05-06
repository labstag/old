<?php

namespace Labstag\Controller\Admin;

use Labstag\Entity\Formbuilder;
use Labstag\Form\Admin\FormType;
use Labstag\Lib\AdminControllerLib;
use Labstag\Repository\UserRepository;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin/formbuilder")
 */
class FormbuilderAdmin extends AdminControllerLib
{
    /**
     * @Route("/", name="adminformbuilder_index", methods={"GET"})
     */
    public function index(): Response
    {
        $datatable = [
            'Username'  => [
                'field'    => 'username',
                'sortable' => true,
            ],
            'Email'     => [
                'field'    => 'email',
                'sortable' => true,
            ],
            'Roles'     => [
                'field'     => 'roles',
                'sortable'  => true,
                'formatter' => 'rolesFormatter',
            ],
            'Avatar'    => [
                'field'     => 'avatar',
                'sortable'  => true,
                'formatter' => 'imageFormatter',
            ],
            'api key'   => [
                'field'    => 'apiKey',
                'sortable' => true,
            ],
            'Enable'    => [
                'field'     => 'enable',
                'sortable'  => true,
                'formatter' => 'enableFormatter',
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
            'title'      => 'Formbuilder list',
            'datatable'  => $datatable,
            'url_enable' => 'adminformbuilder_enable',
            'api'        => 'api_formbuilders_get_collection',
            'url_new'    => 'adminformbuilder_new',
            'url_delete' => 'adminformbuilder_delete',
            'url_edit'   => 'adminformbuilder_edit',
        ];

        return $this->crudListAction($data);
    }

    /**
     * @Route("/new", name="adminformbuilder_new", methods={"GET", "POST"})
     */
    public function new(Request $request): Response
    {
        return $this->crudNewAction(
            $request,
            [
                'entity'    => new Formbuilder(),
                'form'      => FormType::class,
                'url_edit'  => 'adminformbuilder_edit',
                'url_index' => 'adminformbuilder_index',
                'title'     => 'Add new formbuilder',
            ]
        );
    }

    /**
     * @Route("/enable", name="adminformbuilder_enable")
     */
    public function enable(Request $request, UserRepository $repository): JsonResponse
    {
        return $this->crudEnableAction($request, $repository, 'setEnable');
    }

    /**
     * @Route("/edit/{id}", name="adminformbuilder_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, Formbuilder $formbuilder): Response
    {
        return $this->crudEditAction(
            $request,
            [
                'form'       => FormType::class,
                'entity'     => $formbuilder,
                'url_index'  => 'adminformbuilder_index',
                'url_edit'   => 'adminformbuilder_edit',
                'url_delete' => 'adminformbuilder_delete',
                'title'      => 'Edit formbuilder',
            ]
        );
    }

    /**
     * @Route("/", name="adminformbuilder_delete", methods={"DELETE"})
     */
    public function delete(Request $request, UserRepository $repository): JsonResponse
    {
        return $this->crudDeleteAction($request, $repository, 'adminformbuilder_index');
    }
}
