<?php

namespace Labstag\Controller\Admin;

use Labstag\Entity\Template;
use Labstag\Form\Admin\TemplateType;
use Labstag\Lib\AdminControllerLib;
use Labstag\Repository\ConfigurationRepository;
use Labstag\Repository\TemplateRepository;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin/template")
 */
class TemplateAdmin extends AdminControllerLib
{
    /**
     * @Route("/", name="admintemplate_list", methods={"GET"})
     * @Route("/trash", name="admintemplate_trash", methods={"GET"})
     */
    public function list(TemplateRepository $repository): Response
    {
        $datatable = [
            'Name' => [
                'field'      => 'name',
                'switchable' => false,
            ],
            'Code' => ['field' => 'code'],
        ];
        $data      = [
            'title'           => 'Template list',
            'datatable'       => $datatable,
            'repository'      => $repository,
            'url_enable'      => ['enable' => 'admintemplate_enable'],
            'api'             => 'api_templates_get_collection',
            'url_new'         => 'admintemplate_new',
            'graphql_query'   => [
                'table'  => 'templates',
                'node'   => 'id _id name code',
                'params' => '',
            ],
            'url_delete'      => 'admintemplate_delete',
            'url_deletetrash' => 'admintemplate_deletetrash',
            'url_trash'       => 'admintemplate_trash',
            'url_restore'     => 'admintemplate_restore',
            'url_empty'       => 'admintemplate_empty',
            'url_list'        => 'admintemplate_list',
            'url_edit'        => 'admintemplate_edit',
            'url_trashedit'   => 'admintemplate_trashedit',
        ];

        return $this->crudListAction($data);
    }

    /**
     * @Route("/new", name="admintemplate_new", methods={"GET", "POST"})
     */
    public function new(): Response
    {
        return $this->crudNewAction(
            [
                'entity'   => new Template(),
                'form'     => TemplateType::class,
                'url_edit' => 'admintemplate_edit',
                'url_list' => 'admintemplate_list',
                'title'    => 'Add new template',
            ]
        );
    }

    /**
     * @Route("/enable", name="admintemplate_enable")
     */
    public function enable(TemplateRepository $repository): JsonResponse
    {
        return $this->crudEnableAction($repository, 'setEnable');
    }

    /**
     * @Route("/trashedit/{id}", name="admintemplate_trashedit", methods={"GET", "POST"})
     *
     * @param mixed $id
     */
    public function trashEdit(ConfigurationRepository $repository, $id): Response
    {
        $template = $repository->findOneDateInTrash($id);

        return $this->crudEditAction(
            [
                'form'       => TemplateType::class,
                'entity'     => $template,
                'url_list'   => 'admintemplate_trash',
                'url_edit'   => 'admintemplate_trashedit',
                'url_delete' => 'admintemplate_deletetrash',
                'title'      => 'Edit template',
            ]
        );
    }

    /**
     * @Route("/edit/{id}", name="admintemplate_edit", methods={"GET", "POST"})
     */
    public function edit(Template $template): Response
    {
        return $this->crudEditAction(
            [
                'form'       => TemplateType::class,
                'entity'     => $template,
                'url_list'   => 'admintemplate_list',
                'url_edit'   => 'admintemplate_edit',
                'url_delete' => 'admintemplate_delete',
                'title'      => 'Edit template',
            ]
        );
    }

    /**
     * @Route("/empty", name="admintemplate_empty")
     */
    public function emptyFormBuilder(TemplateRepository $repository): JsonResponse
    {
        return $this->crudEmptyAction($repository, 'admintemplate_list');
    }

    /**
     * @Route("/", name="admintemplate_delete", methods={"DELETE"})
     * @Route("/trash", name="admintemplate_deletetrash", methods={"DELETE"})
     */
    public function delete(TemplateRepository $repository): JsonResponse
    {
        return $this->crudDeleteAction(
            $repository,
            [
                'url_list'  => 'admintemplate_list',
                'url_trash' => 'admintemplate_trash',
            ]
        );
    }

    /**
     * @Route("/restore", name="admintemplate_restore")
     */
    public function restore(TemplateRepository $repository): JsonResponse
    {
        return $this->crudRestoreAction(
            $repository,
            [
                'url_list'  => 'admintemplate_list',
                'url_trash' => 'admintemplate_trash',
            ]
        );
    }
}
