<?php

namespace Labstag\Controller\Admin;

use Labstag\Entity\Chapitre;
use Labstag\Lib\AdminControllerLib;
use Labstag\Form\Admin\ChapitreType;
use Labstag\Repository\HistoryRepository;
use Labstag\Repository\ChapitreRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;

/**
 * @Route("/admin/chapitre")
 */
class ChapitreAdmin extends AdminControllerLib
{
    /**
     * @Route("/", name="adminchapitre_index", methods={"GET"})
     */
    public function index(HistoryRepository $repository): Response
    {
        $datatable = [
            'Name'      => [
                'field'    => 'name',
                'sortable' => true,
                'valign'   => 'top',
            ],
            'Histoire'     => [
                'field'    => 'refhistory',
                'sortable' => true,
                'valign'   => 'top',
            ],
            'Enable'    => [
                'field'     => 'enable',
                'sortable'  => true,
                'valign'    => 'top',
                'formatter' => 'enableFormatter',
                'url'       => $this->generateUrl('adminchapitre_enable'),
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
            'title'      => 'Chapitre list',
            'datatable'  => $datatable,
            'api'        => 'api_chapitres_get_collection',
            'url_delete' => 'adminchapitre_delete',
            'url_edit'   => 'adminchapitre_edit',
        ];

        $histoires = $repository->findAll();
        if (count($histoires)) {
            $data['url_new'] = 'adminchapitre_new';
        }

        return $this->crudListAction($data);
    }

    /**
     * @Route("/enable", name="adminchapitre_enable")
     */
    public function enable(Request $request, ChapitreRepository $repository): JsonResponse
    {
        return $this->crudEnableAction($request, $repository);
    }

    /**
     * @Route("/new", name="adminchapitre_new", methods={"GET", "POST"})
     */
    public function new(Request $request, HistoryRepository $repository): Response
    {
        $histoires = $repository->findAll();
        if (count($histoires) == 0) {
            throw new HttpException(403, 'Vous ne pouvez pas créer de nouveau chapitre sans histoires');
        }

        return $this->crudNewAction(
            $request,
            [
                'entity'    => new Chapitre(),
                'form'      => ChapitreType::class,
                'url_edit'  => 'adminchapitre_edit',
                'url_index' => 'adminchapitre_index',
                'title'     => 'Add new chapitre',
            ]
        );
    }

    /**
     * @Route("/edit/{id}", name="adminchapitre_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, Chapitre $chapitre): Response
    {
        return $this->crudEditAction(
            $request,
            [
                'form'       => ChapitreType::class,
                'entity'     => $chapitre,
                'url_index'  => 'adminchapitre_index',
                'url_edit'   => 'adminchapitre_edit',
                'url_delete' => 'adminchapitre_delete',
                'title'      => 'Edit chapitre',
            ]
        );
    }

    /**
     * @Route("/", name="adminchapitre_delete", methods={"DELETE"})
     */
    public function delete(Request $request, ChapitreRepository $repository): JsonResponse
    {
        return $this->crudDeleteAction($request, $repository, 'adminchapitre_index');
    }
}
