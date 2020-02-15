<?php

namespace Labstag\Controller\Admin;

use Labstag\Entity\Templates;
use Labstag\Form\Admin\TemplatesType;
use Labstag\Lib\AdminControllerLib;
use Labstag\Repository\ConfigurationRepository;
use Labstag\Repository\TemplatesRepository;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin/templates")
 */
class TemplatesAdmin extends AdminControllerLib
{
    /**
     * @Route("/", name="admintemplates_list", methods={"GET"})
     * @Route("/trash", name="admintemplates_trash", methods={"GET"})
     */
    public function list(TemplatesRepository $repository): Response
    {
        $datatable = [
            'Name' => [
                'field'      => 'name',
                'switchable' => false,
            ],
            'Code' => ['field' => 'code'],
        ];
        $data      = [
            'title'           => 'Templates list',
            'datatable'       => $datatable,
            'repository'      => $repository,
            'url_enable'      => ['enable' => 'admintemplates_enable'],
            'api'             => 'api_templates_get_collection',
            'url_new'         => 'admintemplates_new',
            'url_delete'      => 'admintemplates_delete',
            'url_deletetrash' => 'admintemplates_deletetrash',
            'url_trash'       => 'admintemplates_trash',
            'url_restore'     => 'admintemplates_restore',
            'url_empty'       => 'admintemplates_empty',
            'url_list'        => 'admintemplates_list',
            'url_edit'        => 'admintemplates_edit',
            'url_trashedit'   => 'admintemplates_trashedit',
        ];

        return $this->crudListAction($data);
    }

    /**
     * @Route("/new", name="admintemplates_new", methods={"GET", "POST"})
     */
    public function new(): Response
    {
        return $this->crudNewAction(
            [
                'entity'   => new Templates(),
                'form'     => TemplatesType::class,
                'url_edit' => 'admintemplates_edit',
                'url_list' => 'admintemplates_list',
                'title'    => 'Add new templates',
            ]
        );
    }

    /**
     * @Route("/enable", name="admintemplates_enable")
     */
    public function enable(TemplatesRepository $repository): JsonResponse
    {
        return $this->crudEnableAction($repository, 'setEnable');
    }

    /**
     * @Route("/trashedit/{id}", name="admintemplates_trashedit", methods={"GET", "POST"})
     *
     * @param mixed $id
     */
    public function trashEdit(ConfigurationRepository $repository, $id): Response
    {
        $templates = $repository->findOneDateInTrash($id);

        return $this->crudEditAction(
            [
                'form'       => TemplatesType::class,
                'entity'     => $templates,
                'url_list'   => 'admintemplates_trash',
                'url_edit'   => 'admintemplates_trashedit',
                'url_delete' => 'admintemplates_deletetrash',
                'title'      => 'Edit templates',
            ]
        );
    }

    /**
     * @Route("/edit/{id}", name="admintemplates_edit", methods={"GET", "POST"})
     */
    public function edit(Templates $templates): Response
    {
        return $this->crudEditAction(
            [
                'form'       => TemplatesType::class,
                'entity'     => $templates,
                'url_list'   => 'admintemplates_list',
                'url_edit'   => 'admintemplates_edit',
                'url_delete' => 'admintemplates_delete',
                'title'      => 'Edit templates',
            ]
        );
    }

    /**
     * @Route("/empty", name="admintemplates_empty")
     */
    public function emptyFormBuilder(TemplatesRepository $repository): JsonResponse
    {
        return $this->crudEmptyAction($repository, 'admintemplates_list');
    }

    /**
     * @Route("/", name="admintemplates_delete", methods={"DELETE"})
     * @Route("/trash", name="admintemplates_deletetrash", methods={"DELETE"})
     */
    public function delete(TemplatesRepository $repository): JsonResponse
    {
        return $this->crudDeleteAction(
            $repository,
            [
                'url_list'  => 'admintemplates_list',
                'url_trash' => 'admintemplates_trash',
            ]
        );
    }

    /**
     * @Route("/restore", name="admintemplates_restore")
     */
    public function restore(TemplatesRepository $repository): JsonResponse
    {
        return $this->crudRestoreAction(
            $repository,
            [
                'url_list'  => 'admintemplates_list',
                'url_trash' => 'admintemplates_trash',
            ]
        );
    }
}
