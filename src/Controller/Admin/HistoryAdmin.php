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
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin/history")
 */
class HistoryAdmin extends AdminControllerLib
{
    /**
     * @Route("/", name="adminhistory_list", methods={"GET"})
     * @Route("/trash", name="adminhistory_trash", methods={"GET"})
     */
    public function list(HistoryRepository $repository): Response
    {
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
            'title'           => 'History list',
            'datatable'       => $datatable,
            'repository'      => $repository,
            'api'             => 'api_histories_get_collection',
            'url_new'         => 'adminhistory_new',
            'url_delete'      => 'adminhistory_delete',
            'url_deletetrash' => 'adminhistory_deletetrash',
            'url_trash'       => 'adminhistory_trash',
            'url_list'        => 'adminhistory_list',
            'url_edit'        => 'adminhistory_edit',
            'url_enable'      => [
                'enable' => 'adminhistory_enable',
                'end'    => 'adminhistory_end',
            ],
        ];

        return $this->crudListAction($data);
    }

    /**
     * @Route("/enable", name="adminhistory_enable")
     */
    public function enable(HistoryRepository $repository): JsonResponse
    {
        return $this->crudEnableAction($repository, 'setEnd');
    }

    /**
     * @Route("/end", name="adminhistory_end")
     */
    public function end(HistoryRepository $repository): JsonResponse
    {
        return $this->crudEnableAction($repository, 'setEnable');
    }

    /**
     * @Route("/new", name="adminhistory_new", methods={"GET", "POST"})
     */
    public function new(): Response
    {
        return $this->crudNewAction(
            [
                'entity'   => new History(),
                'form'     => HistoryType::class,
                'url_edit' => 'adminhistory_edit',
                'url_list' => 'adminhistory_list',
                'title'    => 'Add new history',
            ]
        );
    }

    /**
     * @Route("/trash/edit/{id}", name="adminhistory_trashedit", methods={"GET", "POST"})
     * @return Response
     */
    public function trashedit($id, HistoryRepository $repository): Response
    {
        $em = $this->getDoctrine()->getManager();
        $em->getFilters()->disable('softdeleteable');
        $history = $em;
        return $this->crudEditAction(
            [
                'form'       => HistoryType::class,
                'entity'     => $history,
                'url_list'   => 'adminhistory_trash',
                'url_edit'   => 'adminhistory_trashedit',
                'url_delete' => 'adminhistory_deletetrash',
                'title'      => 'Edit history',
            ]
        );
    }

    /**
     * @Route("/edit/{id}", name="adminhistory_edit", methods={"GET", "POST"})
     */
    public function edit(History $history): Response
    {
        return $this->crudEditAction(
            [
                'form'       => HistoryType::class,
                'entity'     => $history,
                'url_list'   => 'adminhistory_list',
                'url_edit'   => 'adminhistory_edit',
                'url_delete' => 'adminhistory_delete',
                'title'      => 'Edit history',
            ]
        );
    }

    /**
     * @Route("/", name="adminhistory_delete", methods={"DELETE"})
     * @Route("/trash", name="adminhistory_deletetrash", methods={"DELETE"})
     */
    public function delete(HistoryRepository $repository): JsonResponse
    {
        return $this->crudDeleteAction(
            $repository,
            [
                'url_list'  => 'adminhistory_list',
                'url_trash' => 'adminhistory_trash'
            ]
        );
    }

    /**
     * @Route("/chapitre/", name="adminhistorychapitre_list", methods={"GET"})
     * @Route("/chapitre/trash", name="adminhistorychapitre_trash", methods={"GET"})
     */
    public function listChapitre(ChapitreRepository $repository): Response
    {
        $datatable = [
            'Name'      => ['field' => 'name'],
            'Histoire'  => [
                'field'     => 'refhistory',
                'formatter' => 'dataFormatter',
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
            'title'           => 'Chapitre list',
            'datatable'       => $datatable,
            'repository'      => $repository,
            'api'             => 'api_chapitres_get_collection',
            'url_delete'      => 'adminhistorychapitre_delete',
            'url_deletetrash' => 'adminhistorychapitre_deletetrash',
            'url_trash'       => 'adminhistorychapitre_trash',
            'url_list'        => 'adminhistorychapitre_list',
            'url_edit'        => 'adminhistorychapitre_edit',
        ];

        $histoires = $repository->findAll();
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
    public function enableChapitre(ChapitreRepository $repository): JsonResponse
    {
        return $this->crudEnableAction($repository, 'setEnable');
    }

    /**
     * @Route("/chapitre/new", name="adminhistorychapitre_new", methods={"GET", "POST"})
     */
    public function newChapitre(HistoryRepository $repository): Response
    {
        $histoires = $repository->findAll();
        if (0 == count($histoires)) {
            throw new HttpException(403, "Vous ne pouvez pas créer de chapitre sans créer d'histoires");
        }

        return $this->crudNewAction(
            [
                'entity'   => new Chapitre(),
                'form'     => ChapitreType::class,
                'url_edit' => 'adminhistorychapitre_edit',
                'url_list' => 'adminhistorychapitre_list',
                'title'    => 'Add new chapitre',
            ]
        );
    }

    /**
     * @Route("/chapitre/edit/{id}", name="adminhistorychapitre_edit", methods={"GET", "POST"})
     */
    public function editChapitre(Chapitre $chapitre): Response
    {
        return $this->crudEditAction(
            [
                'form'       => ChapitreType::class,
                'entity'     => $chapitre,
                'url_list'   => 'adminhistorychapitre_list',
                'url_edit'   => 'adminhistorychapitre_edit',
                'url_delete' => 'adminhistorychapitre_delete',
                'title'      => 'Edit chapitre',
            ]
        );
    }

    /**
     * @Route("/chapitre/", name="adminhistorychapitre_delete", methods={"DELETE"})
     * @Route("/chapitre/trash", name="adminhistorychapitre_deletetrash", methods={"DELETE"})
     */
    public function deleteChapitre(ChapitreRepository $repository): JsonResponse
    {
        return $this->crudDeleteAction(
            $repository,
            [
                'url_list'  => 'adminhistorychapitre_list',
                'url_trash' => 'adminhistorychapitre_trash'
            ]
        );
    }
}
