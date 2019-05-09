<?php

namespace Labstag\Controller\Admin;

use Labstag\Entity\Formbuilder;
use Labstag\Form\Admin\FormbuilderType;
use Labstag\Lib\AdminControllerLib;
use Labstag\Repository\ConfigurationRepository;
use Labstag\Repository\FormbuilderRepository;
use Labstag\Repository\UserRepository;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin/formbuilder")
 */
class FormBuilderAdmin extends AdminControllerLib
{
    /**
     * @Route("/", name="adminformbuilder_list", methods={"GET"})
     * @Route("/trash", name="adminformbuilder_trash", methods={"GET"})
     */
    public function list(FormbuilderRepository $repository): Response
    {
        $datatable = [
            'Name' => ['field' => 'name'],
        ];
        $data      = [
            'title'           => 'Formbuilder list',
            'datatable'       => $datatable,
            'repository'      => $repository,
            'url_enable'      => ['enable' => 'adminformbuilder_enable'],
            'api'             => 'api_formbuilders_get_collection',
            'url_new'         => 'adminformbuilder_new',
            'url_delete'      => 'adminformbuilder_delete',
            'url_deletetrash' => 'adminformbuilder_deletetrash',
            'url_trash'       => 'adminformbuilder_trash',
            'url_empty'       => 'adminformbuilder_empty',
            'url_list'        => 'adminformbuilder_list',
            'url_edit'        => 'adminformbuilder_edit',
            'url_trashedit'   => 'adminformbuilder_trashedit',
        ];

        return $this->crudListAction($data);
    }

    /**
     * @Route("/new", name="adminformbuilder_new", methods={"GET", "POST"})
     */
    public function new(): Response
    {
        return $this->crudNewAction(
            [
                'twig'     => 'admin/formbuilder.html.twig',
                'entity'   => new Formbuilder(),
                'form'     => FormbuilderType::class,
                'url_edit' => 'adminformbuilder_edit',
                'url_list' => 'adminformbuilder_list',
                'title'    => 'Add new formbuilder',
            ]
        );
    }

    /**
     * @Route("/enable", name="adminformbuilder_enable")
     */
    public function enable(UserRepository $repository): JsonResponse
    {
        return $this->crudEnableAction($repository, 'setEnable');
    }

    /**
     * @Route("/trashedit/{id}", name="adminformbuilder_trashedit", methods={"GET", "POST"})
     */
    public function trashEdit(ConfigurationRepository $repository, $id): Response
    {
        $formbuilder = $repository->findOneDateInTrash($id);

        return $this->crudEditAction(
            [
                'twig'       => 'admin/formbuilder.html.twig',
                'form'       => FormbuilderType::class,
                'entity'     => $formbuilder,
                'url_list'   => 'adminformbuilder_trash',
                'url_edit'   => 'adminformbuilder_trashedit',
                'url_delete' => 'adminformbuilder_deletetrash',
                'title'      => 'Edit formbuilder',
            ]
        );
    }

    /**
     * @Route("/edit/{id}", name="adminformbuilder_edit", methods={"GET", "POST"})
     */
    public function edit(Formbuilder $formbuilder): Response
    {
        return $this->crudEditAction(
            [
                'twig'       => 'admin/formbuilder.html.twig',
                'form'       => FormbuilderType::class,
                'entity'     => $formbuilder,
                'url_list'   => 'adminformbuilder_list',
                'url_edit'   => 'adminformbuilder_edit',
                'url_delete' => 'adminformbuilder_delete',
                'title'      => 'Edit formbuilder',
            ]
        );
    }

    /**
     * @Route("/empty", name="adminformbuilder_empty")
     */
    public function empty(UserRepository $repository): JsonResponse
    {
        return $this->crudEmptyAction($repository, 'adminformbuilder_list');
    }

    /**
     * @Route("/", name="adminformbuilder_delete", methods={"DELETE"})
     * @Route("/trash", name="adminformbuilder_deletetrash", methods={"DELETE"})
     */
    public function delete(UserRepository $repository): JsonResponse
    {
        return $this->crudDeleteAction(
            $repository,
            [
                'url_list'  => 'adminformbuilder_list',
                'url_trash' => 'adminformbuilder_trash',
            ]
        );
    }
}
