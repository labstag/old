<?php

namespace Labstag\Controller\Admin;

use Labstag\Entity\User;
use Labstag\Form\Admin\UserType;
use Labstag\Lib\AdminControllerLib;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Labstag\Repository\UserRepository;

/**
 * @Route("/admin/user")
 */
class UserAdmin extends AdminControllerLib
{
    /**
     * @Route("/", name="adminuser_index", methods={"GET"})
     */
    public function index(): Response
    {
        $datatable = [
            'Username'      => [
                'field'    => 'username',
                'sortable' => true,
                'valign'   => 'top',
            ],
            'Email' => [
                'field'    => 'email',
                'sortable' => true,
                'valign'   => 'top',
            ],
            'Roles' => [
                'field'    => 'roles',
                'sortable' => true,
                'valign'   => 'top',
            ],
            'Avatar'      => [
                'field'     => 'avatar',
                'sortable'  => true,
                'formatter' => 'imageFormatter',
                'valign'    => 'top',
                'align'     => 'center',
            ],
            'api key' => [
                'field'    => 'apiKey',
                'sortable' => true,
                'valign'   => 'top',
            ],
            'Enable' => [
                'field'    => 'enable',
                'sortable' => true,
                'valign'   => 'top',
            ],
            'CreatedAt' => [
                'field'     => 'createdAt',
                'sortable'  => true,
                'formatter' => 'dateFormatter',
                'valign'    => 'top',
            ],
            'UpdatedAt' => [
                'field'     => 'updatedAt',
                'sortable'  => true,
                'formatter' => 'dateFormatter',
                'valign'    => 'top',
            ],
        ];
        $data      = [
            'title'      => 'Users index',
            'datatable'  => $datatable,
            'api'        => 'api_users_get_collection',
            'url_new'    => 'adminuser_new',
            'url_delete' => 'adminuser_delete',
            'url_edit'   => 'adminuser_edit',
        ];
        return $this->crudListAction($data);
    }

    /**
     * @Route("/new", name="adminuser_new", methods={"GET", "POST"})
     */
    public function new(Request $request): Response
    {
        return $this->crudNewAction(
            $request,
            [
                'entity'    => new User(),
                'form'      => UserType::class,
                'url_edit'  => 'adminuser_edit',
                'url_index' => 'adminuser_index',
                'title'     => 'Add new user',
            ]
        );
    }

    /**
     * @Route("/edit/{id}", name="adminuser_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, User $user): Response
    {
        return $this->crudEditAction(
            $request,
            [
                'form'      => UserType::class,
                'entity'    => $user,
                'url_index' => 'adminuser_index',
                'url_edit'  => 'adminuser_edit',
                'url_delete'  => 'adminuser_delete',
                'title'     => 'Edit user',
            ]
        );
    }

    /**
     * @Route("/delete", name="adminuser_delete", methods={"DELETE"})
     */
    public function delete(Request $request, UserRepository $repository): Response
    {
        return $this->crudActionDelete($request, $repository, 'adminuser_index');
    }
}
