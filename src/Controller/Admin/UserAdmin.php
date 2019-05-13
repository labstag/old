<?php

namespace Labstag\Controller\Admin;

use Labstag\Entity\User;
use Labstag\Form\Admin\UserType;
use Labstag\Lib\AdminControllerLib;
use Labstag\Repository\UserRepository;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin/user")
 */
class UserAdmin extends AdminControllerLib
{
    /**
     * @Route("/", name="adminuser_list", methods={"GET"})
     * @Route("/trash", name="adminuser_trash", methods={"GET"})
     */
    public function list(UserRepository $repository): Response
    {
        $datatable = [
            'Username'  => ['field' => 'username'],
            'Email'     => ['field' => 'email'],
            'Roles'     => [
                'field'     => 'roles',
                'formatter' => 'rolesFormatter',
            ],
            'Avatar'    => [
                'field'     => 'avatar',
                'formatter' => 'imageFormatter',
            ],
            'api key'   => ['field' => 'apiKey'],
            'Enable'    => [
                'field'     => 'enable',
                'formatter' => 'enableFormatter',
            ],
            'CreatedAt' => [
                'field'     => 'createdAt',
                'formatter' => 'dateFormatter',
            ],
            'UpdatedAt' => [
                'field'     => 'updatedAt',
                'formatter' => 'dateFormatter',
            ],
        ];
        $data      = [
            'title'           => 'Users list',
            'datatable'       => $datatable,
            'repository'      => $repository,
            'url_enable'      => ['enable' => 'adminuser_enable'],
            'api'             => 'api_users_get_collection',
            'url_new'         => 'adminuser_new',
            'url_delete'      => 'adminuser_delete',
            'url_deletetrash' => 'adminuser_deletetrash',
            'url_trash'       => 'adminuser_trash',
            'url_restore'     => 'adminuser_restore',
            'url_empty'       => 'adminuser_empty',
            'url_list'        => 'adminuser_list',
            'url_edit'        => 'adminuser_edit',
            'url_trashedit'   => 'adminuser_trashedit',
        ];

        return $this->crudListAction($data);
    }

    /**
     * @Route("/new", name="adminuser_new", methods={"GET", "POST"})
     */
    public function new(): Response
    {
        return $this->crudNewAction(
            [
                'entity'   => new User(),
                'form'     => UserType::class,
                'url_edit' => 'adminuser_edit',
                'url_list' => 'adminuser_list',
                'title'    => 'Add new user',
            ]
        );
    }

    /**
     * @Route("/enable", name="adminuser_enable")
     */
    public function enable(UserRepository $repository): JsonResponse
    {
        return $this->crudEnableAction($repository, 'setEnable');
    }

    /**
     * @Route("/trashedit/{guid}", name="adminuser_trashedit", methods={"GET", "POST"})
     */
    public function trashEdit(UserRepository $repository, $guid): Response
    {
        $user = $repository->findOneDateInTrash($guid);

        return $this->crudEditAction(
            [
                'form'       => UserType::class,
                'entity'     => $user,
                'url_list'   => 'adminuser_trash',
                'url_edit'   => 'adminuser_trashedit',
                'url_delete' => 'adminuser_deletetrash',
                'title'      => 'Edit user',
            ]
        );
    }

    /**
     * @Route("/edit/{guid}", name="adminuser_edit", methods={"GET", "POST"})
     */
    public function edit(User $user): Response
    {
        return $this->crudEditAction(
            [
                'form'       => UserType::class,
                'entity'     => $user,
                'url_list'   => 'adminuser_list',
                'url_edit'   => 'adminuser_edit',
                'url_delete' => 'adminuser_delete',
                'title'      => 'Edit user',
            ]
        );
    }

    /**
     * @Route("/", name="adminuser_delete", methods={"DELETE"})
     * @Route("/trash", name="adminuser_deletetrash", methods={"DELETE"})
     */
    public function delete(UserRepository $repository): JsonResponse
    {
        return $this->crudDeleteAction(
            $repository,
            [
                'url_list'  => 'adminuser_list',
                'url_trash' => 'adminuser_trash',
            ]
        );
    }

    /**
     * @Route("/restore", name="adminuser_restore")
     */
    public function restore(UserRepository $repository): JsonResponse
    {
        return $this->crudRestoreAction(
            $repository,
            [
                'url_list'  => 'adminuser_list',
                'url_trash' => 'adminuser_trash',
            ]
        );
    }

    /**
     * @Route("/empty", name="adminuser_empty")
     */
    public function emptyUser(UserRepository $repository): JsonResponse
    {
        return $this->crudEmptyAction($repository, 'adminuser_list');
    }
}
