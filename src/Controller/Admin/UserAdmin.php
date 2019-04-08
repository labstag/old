<?php

namespace Labstag\Controller\Admin;

use Labstag\Entity\User;
use Labstag\Form\Admin\UserType;
use Labstag\Lib\AdminAbstractControllerLib;
use Labstag\Repository\UserRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin/user")
 */
class UserAdmin extends AdminAbstractControllerLib
{
    /**
     * @Route("/", name="adminuser_index", methods={"GET"})
     */
    public function index(UserRepository $userRepository): Response
    {
        $users = $userRepository->findAll();
        $this->paginator($users);

        return $this->twig('admin/user/index.html.twig');
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
                'title'     => 'Edit user',
            ]
        );
    }

    /**
     * @Route("/delete/{id}", name="adminuser_delete", methods={"DELETE"})
     */
    public function delete(Request $request, User $user): Response
    {
        return $this->crudActionDelete($request, $user, 'adminuser_index');
    }
}
