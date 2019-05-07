<?php

namespace Labstag\Controller\Admin;

use Labstag\Entity\Chapitre;
use Labstag\Entity\History;
use Labstag\Form\Admin\ChapitreType;
use Labstag\Form\Admin\HistoryType;
use Labstag\Lib\AdminControllerLib;
use Labstag\Repository\ChapitreRepository;
use Labstag\Repository\HistoryRepository;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin/history")
 */
class HistoryAdmin extends AdminControllerLib
{
    /**
     * @Route("/", name="adminhistory_index", methods={"GET"})
     */
    public function index(HistoryRepository $repository): Response
    {
        $total     = count($repository->findAll());
        $datatable = [
            'Name'      => ['field' => 'name'],
            'User'      => [
                'field'     => 'refuser',
                'formatter' => 'dataFormatter',
            ],
            'Chapitres' => [
                'field'     => 'chapitres',
                'formatter' => 'dataTotalFormatter',
            ],
            'File'      => [
                'field'     => 'file',
                'formatter' => 'imageFormatter',
            ],
            'Fin'       => [
                'field'     => 'end',
                'formatter' => 'endFormatter',
                'url'       => $this->generateUrl('adminhistory_end'),
            ],
            'Enable'    => [
                'field'     => 'enable',
                'formatter' => 'enableFormatter',
                'url'       => $this->generateUrl('adminhistory_enable'),
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
            'title'      => 'History list',
            'total'      => $total,
            'datatable'  => $datatable,
            'api'        => 'api_histories_get_collection',
            'url_new'    => 'adminhistory_new',
            'url_delete' => 'adminhistory_delete',
            'url_edit'   => 'adminhistory_edit',
            'url_enable' => [
                'enable' => 'adminhistory_enable',
                'end'    => 'adminhistory_end',
            ],
        ];

        return $this->crudListAction($data);
    }

    /**
     * @Route("/enable", name="adminhistory_enable")
     */
    public function enable(Request $request, HistoryRepository $repository): JsonResponse
    {
        return $this->crudEnableAction($request, $repository, 'setEnd');
    }

    /**
     * @Route("/end", name="adminhistory_end")
     */
    public function end(Request $request, HistoryRepository $repository): JsonResponse
    {
        return $this->crudEnableAction($request, $repository, 'setEnable');
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

    /**
     * @Route("/chapitre/", name="adminhistorychapitre_index", methods={"GET"})
     */
    public function indexChapitre(ChapitreRepository $chapitreRepository, HistoryRepository $histoireRepository): Response
    {
        $total     = count($chapitreRepository->findAll());
        $datatable = [
            'Name'      => ['field' => 'name'],
            'Histoire'  => ['field' => 'refhistory'],
            'File'      => [
                'field'     => 'file',
                'formatter' => 'imageFormatter',
            ],
            'Page'      => ['field' => 'page'],
            'Enable'    => [
                'field'     => 'enable',
                'formatter' => 'enableFormatter',
                'url'       => $this->generateUrl('adminhistorychapitre_enable'),
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
            'title'      => 'Chapitre list',
            'total'      => $total,
            'datatable'  => $datatable,
            'api'        => 'api_chapitres_get_collection',
            'url_delete' => 'adminhistorychapitre_delete',
            'url_edit'   => 'adminhistorychapitre_edit',
        ];

        $histoires = $histoireRepository->findAll();
        if (count($histoires)) {
            $data['url_new'] = 'adminhistorychapitre_new';
        } else {
            $this->addFlash('warning', "Vous ne pouvez pas créer de chapitre sans créer d'histoires");
        }

        return $this->crudListAction($data);
    }

    /**
     * @Route("/chapitre/enable", name="adminhistorychapitre_enable")
     */
    public function enableChapitre(Request $request, ChapitreRepository $repository): JsonResponse
    {
        return $this->crudEnableAction($request, $repository, 'setEnable');
    }

    /**
     * @Route("/chapitre/new", name="adminhistorychapitre_new", methods={"GET", "POST"})
     */
    public function newChapitre(Request $request, HistoryRepository $repository): Response
    {
        $histoires = $repository->findAll();
        if (0 == count($histoires)) {
            throw new HttpException(403, "Vous ne pouvez pas créer de chapitre sans créer d'histoires");
        }

        return $this->crudNewAction(
            $request,
            [
                'entity'    => new Chapitre(),
                'form'      => ChapitreType::class,
                'url_edit'  => 'adminhistorychapitre_edit',
                'url_index' => 'adminhistorychapitre_index',
                'title'     => 'Add new chapitre',
            ]
        );
    }

    /**
     * @Route("/chapitre/edit/{id}", name="adminhistorychapitre_edit", methods={"GET", "POST"})
     */
    public function editChapitre(Request $request, Chapitre $chapitre): Response
    {
        return $this->crudEditAction(
            $request,
            [
                'form'       => ChapitreType::class,
                'entity'     => $chapitre,
                'url_index'  => 'adminhistorychapitre_index',
                'url_edit'   => 'adminhistorychapitre_edit',
                'url_delete' => 'adminhistorychapitre_delete',
                'title'      => 'Edit chapitre',
            ]
        );
    }

    /**
     * @Route("/chapitre/", name="adminhistorychapitre_delete", methods={"DELETE"})
     */
    public function deleteChapitre(Request $request, ChapitreRepository $repository): JsonResponse
    {
        return $this->crudDeleteAction($request, $repository, 'adminhistorychapitre_index');
    }
}
