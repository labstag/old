<?php

namespace Labstag\Controller\Admin;

use Labstag\Entity\History;
use Labstag\Form\Admin\HistoryType;
use Labstag\Lib\AdminControllerLib;
use Labstag\Repository\HistoryRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;

/**
 * @Route("/admin/history")
 */
class HistoryAdmin extends AdminControllerLib
{
    /**
     * @Route("/", name="adminhistory_index", methods={"GET"})
     */
    public function index(): Response
    {
        $datatable = [
            'Name'      => [
                'field'    => 'name',
                'sortable' => true,
                'valign'   => 'top',
            ],
            'User'      => [
                'field'    => 'refuser',
                'sortable' => true,
                'valign'   => 'top',
            ],
            'Enable'    => [
                'field'     => 'enable',
                'sortable'  => true,
                'valign'    => 'top',
                'formatter' => 'enableFormatter',
                'url'       => $this->generateUrl('adminhistory_enable'),
                'align'     => 'right',
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
            'title'      => 'History list',
            'datatable'  => $datatable,
            'api'        => 'api_histories_get_collection',
            'url_new'    => 'adminhistory_new',
            'url_delete' => 'adminhistory_delete',
            'url_edit'   => 'adminhistory_edit',
        ];

        return $this->crudListAction($data);
    }

    /**
     * @Route("/enable", name="adminhistory_enable")
     */
    public function enable(Request $request, HistoryRepository $repository): JsonResponse
    {
        return $this->crudEnableAction($request, $repository);
    }

    /**
     * @Route("/new", name="adminhistory_new", methods={"GET", "POST"})
     */
    public function new(Request $request): Response
    {
        return $this->crudNewAction(
            $request,
            [
                'entity'    => new History(),
                'form'      => HistoryType::class,
                'url_edit'  => 'adminhistory_edit',
                'url_index' => 'adminhistory_index',
                'title'     => 'Add new history',
            ]
        );
    }

    /**
     * @Route("/edit/{id}", name="adminhistory_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, History $history): Response
    {
        return $this->crudEditAction(
            $request,
            [
                'form'       => HistoryType::class,
                'entity'     => $history,
                'url_index'  => 'adminhistory_index',
                'url_edit'   => 'adminhistory_edit',
                'url_delete' => 'adminhistory_delete',
                'title'      => 'Edit history',
            ]
        );
    }

    /**
     * @Route("/", name="adminhistory_delete", methods={"DELETE"})
     */
    public function delete(Request $request, HistoryRepository $repository): JsonResponse
    {
        return $this->crudDeleteAction($request, $repository, 'adminhistory_index');
    }
}
