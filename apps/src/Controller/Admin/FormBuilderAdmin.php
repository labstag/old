<?php

namespace Labstag\Controller\Admin;

use Labstag\Entity\Formbuilder;
use Labstag\Form\Admin\FormbuilderType;
use Labstag\Form\Admin\FormbuilderViewType;
use Labstag\Lib\AdminControllerLib;
use Labstag\Repository\ConfigurationRepository;
use Labstag\Repository\FormbuilderRepository;
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
            'Name'             => [
                'field'      => 'name',
                'switchable' => false,
            ],
            'Nombre de champs' => [
                'field'     => 'formbuilder',
                'formatter' => 'dataFormBuilderFormatter',
            ],
        ];
        $data      = [
            'title'           => 'Formbuilder list',
            'datatable'       => $datatable,
            'repository'      => $repository,
            'url_enable'      => ['enable' => 'adminformbuilder_enable'],
            'api'             => 'api_formbuilders_get_collection',
            'url_new'         => 'adminformbuilder_new',
            'graphql_query'   => [
                'table'  => 'formbuilders',
                'node'   => 'id _id name createdAt updatedAt',
                'params' => '',
            ],
            'url_delete'      => 'adminformbuilder_delete',
            'url_deletetrash' => 'adminformbuilder_deletetrash',
            'url_trash'       => 'adminformbuilder_trash',
            'url_restore'     => 'adminformbuilder_restore',
            'url_empty'       => 'adminformbuilder_empty',
            'url_list'        => 'adminformbuilder_list',
            'url_edit'        => 'adminformbuilder_edit',
            'url_trashedit'   => 'adminformbuilder_trashedit',
            'url_view'        => 'adminformbuilder_view',
            'url_trashview'   => 'adminformbuilder_trashview',
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
                'twig'     => 'admin/formbuilder/form.html.twig',
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
    public function enable(FormBuilderRepository $repository): JsonResponse
    {
        return $this->crudEnableAction($repository, 'setEnable');
    }

    /**
     * @Route("/trashview/{id}", name="adminformbuilder_trashview")
     *
     * @param mixed $id
     */
    public function trashView(ConfigurationRepository $repository, $id): Response
    {
        $formbuilder = $repository->findOneDateInTrash($id);

        return $this->viewForm($formbuilder);
    }

    /**
     * @Route("/view/{id}", name="adminformbuilder_view")
     */
    public function view(Formbuilder $formbuilder): Response
    {
        return $this->viewForm($formbuilder);
    }

    /**
     * @Route("/trashedit/{id}", name="adminformbuilder_trashedit", methods={"GET", "POST"})
     *
     * @param mixed $id
     */
    public function trashEdit(ConfigurationRepository $repository, $id): Response
    {
        $formbuilder = $repository->findOneDateInTrash($id);

        return $this->crudEditAction(
            [
                'twig'       => 'admin/formbuilder/form.html.twig',
                'form'       => FormbuilderType::class,
                'entity'     => $formbuilder,
                'url_list'   => 'adminformbuilder_trash',
                'url_edit'   => 'adminformbuilder_trashedit',
                'url_view'   => 'adminformbuilder_trashview',
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
                'twig'       => 'admin/formbuilder/form.html.twig',
                'form'       => FormbuilderType::class,
                'entity'     => $formbuilder,
                'url_list'   => 'adminformbuilder_list',
                'url_edit'   => 'adminformbuilder_edit',
                'url_view'   => 'adminformbuilder_view',
                'url_delete' => 'adminformbuilder_delete',
                'title'      => 'Edit formbuilder',
            ]
        );
    }

    /**
     * @Route("/empty", name="adminformbuilder_empty")
     */
    public function emptyFormBuilder(FormBuilderRepository $repository): JsonResponse
    {
        return $this->crudEmptyAction($repository, 'adminformbuilder_list');
    }

    /**
     * @Route("/", name="adminformbuilder_delete", methods={"DELETE"})
     * @Route("/trash", name="adminformbuilder_deletetrash", methods={"DELETE"})
     */
    public function delete(FormBuilderRepository $repository): JsonResponse
    {
        return $this->crudDeleteAction(
            $repository,
            [
                'url_list'  => 'adminformbuilder_list',
                'url_trash' => 'adminformbuilder_trash',
            ]
        );
    }

    /**
     * @Route("/restore", name="adminformbuilder_restore")
     */
    public function restore(FormBuilderRepository $repository): JsonResponse
    {
        return $this->crudRestoreAction(
            $repository,
            [
                'url_list'  => 'adminformbuilder_list',
                'url_trash' => 'adminformbuilder_trash',
            ]
        );
    }

    private function viewForm(Formbuilder $formbuilder): Response
    {
        $route   = $this->request->attributes->get('_route');
        $urlList = 'adminformbuilder_list';
        $urlEdit = 'adminformbuilder_edit';
        if (0 != substr_count($route, 'trash')) {
            $urlList = 'adminformbuilder_trash';
            $urlList = 'adminformbuilder_trashedit';
        }

        /** @var array $data */
        $data = json_decode((string) $formbuilder->getFormbuilder(), true);
        $form = $this->createForm(
            FormbuilderViewType::class,
            [],
            [
                'attr' => ['onsubmit' => 'return false;'],
                'data' => $data,
            ]
        );

        return $this->twig(
            'admin/formbuilder/view.html.twig',
            [
                'entity'   => $formbuilder,
                'url_edit' => $urlEdit,
                'url_list' => $urlList,
                'title'    => 'formbuilder View',
                'form'     => $form->createView(),
            ]
        );
    }
}
