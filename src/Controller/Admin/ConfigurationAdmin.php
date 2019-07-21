<?php

namespace Labstag\Controller\Admin;

use Labstag\Entity\Configuration;
use Labstag\Form\Admin\ConfigurationType;
use Labstag\Lib\AdminControllerLib;
use Labstag\Repository\ConfigurationRepository;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin/configuration")
 */
class ConfigurationAdmin extends AdminControllerLib
{
    /**
     * @Route("/", name="adminconfiguration_list", methods={"GET"})
     * @Route("/trash", name="adminconfiguration_trash", methods={"GET"})
     */
    public function list(ConfigurationRepository $repository): Response
    {
        $datatable = [
            'Name'      => ['field' => 'name', 'switchable' => false],
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
            'title'           => 'Configuration list',
            'repository'      => $repository,
            'datatable'       => $datatable,
            'api'             => 'api_configurations_get_collection',
            'url_new'         => 'adminconfiguration_new',
            'url_delete'      => 'adminconfiguration_delete',
            'url_deletetrash' => 'adminconfiguration_deletetrash',
            'url_list'        => 'adminconfiguration_list',
            'url_trash'       => 'adminconfiguration_trash',
            'url_restore'     => 'adminconfiguration_restore',
            'url_empty'       => 'adminconfiguration_empty',
            'url_edit'        => 'adminconfiguration_edit',
            'url_trashedit'   => 'adminconfiguration_trashedit',
        ];

        return $this->crudListAction($data);
    }

    /**
     * @Route("/new", name="adminconfiguration_new", methods={"GET", "POST"})
     */
    public function new(): Response
    {
        return $this->crudNewAction(
            [
                'entity'   => new Configuration(),
                'form'     => ConfigurationType::class,
                'url_edit' => 'adminconfiguration_edit',
                'url_list' => 'adminconfiguration_list',
                'title'    => 'Add new configuration',
            ]
        );
    }

    /**
     * @Route("/trashedit/{id}", name="adminconfiguration_trashedit", methods={"GET", "POST"})
     */
    public function trashEdit(ConfigurationRepository $repository, $id): Response
    {
        $configuration = $repository->findOneDateInTrash($id);

        return $this->crudEditAction(
            [
                'form'       => ConfigurationType::class,
                'entity'     => $configuration,
                'url_list'   => 'adminconfiguration_trash',
                'url_edit'   => 'adminconfiguration_trashedit',
                'url_delete' => 'adminconfiguration_deletetrash',
                'title'      => 'Edit configuration',
            ]
        );
    }

    /**
     * @Route("/edit/{id}", name="adminconfiguration_edit", methods={"GET", "POST"})
     */
    public function edit(Configuration $configuration): Response
    {
        return $this->crudEditAction(
            [
                'form'       => ConfigurationType::class,
                'entity'     => $configuration,
                'url_list'   => 'adminconfiguration_list',
                'url_edit'   => 'adminconfiguration_edit',
                'url_delete' => 'adminconfiguration_delete',
                'title'      => 'Edit configuration',
            ]
        );
    }

    /**
     * @Route("/empty", name="adminconfiguration_empty")
     */
    public function emptyConfiguration(ConfigurationRepository $repository): JsonResponse
    {
        return $this->crudEmptyAction($repository, 'adminconfiguration_list');
    }

    /**
     * @Route("/restore", name="adminconfiguration_restore")
     */
    public function restore(ConfigurationRepository $repository): JsonResponse
    {
        return $this->crudRestoreAction(
            $repository,
            [
                'url_list'  => 'adminconfiguration_list',
                'url_trash' => 'adminconfiguration_trash',
            ]
        );
    }

    /**
     * @Route("/", name="adminconfiguration_delete", methods={"DELETE"})
     * @Route("/trash", name="adminconfiguration_deletetrash", methods={"DELETE"})
     */
    public function delete(ConfigurationRepository $repository): JsonResponse
    {
        return $this->crudDeleteAction(
            $repository,
            [
                'url_list'  => 'adminconfiguration_list',
                'url_trash' => 'adminconfiguration_trash',
            ]
        );
    }
}
