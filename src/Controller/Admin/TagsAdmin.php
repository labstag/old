<?php

namespace Labstag\Controller\Admin;

use Labstag\Entity\Tags;
use Labstag\Form\Admin\TagsType;
use Labstag\Lib\AdminControllerLib;
use Labstag\Repository\TagsRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin/tags")
 */
class TagsAdmin extends AdminControllerLib
{
    /**
     * @Route("/", name="admintags_index", methods={"GET"})
     */
    public function index(): Response
    {
        $datatable = [
            'Name'      => [
                'field'    => 'name',
                'sortable' => true,
                'valign'   => 'top',
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
            'title'      => 'Tags index',
            'datatable'  => $datatable,
            'api'        => 'api_tags_get_collection',
            'url_new'    => 'admintags_new',
            'url_delete' => 'admintags_delete',
            'url_edit'   => 'admintags_edit',
        ];

        return $this->crudListAction($data);
    }

    /**
     * @Route("/new", name="admintags_new", methods={"GET", "POST"})
     */
    public function new(Request $request): Response
    {
        return $this->crudNewAction(
            $request,
            [
                'entity'    => new Tags(),
                'form'      => TagsType::class,
                'url_edit'  => 'admintags_edit',
                'url_index' => 'admintags_index',
                'title'     => 'Add new tag',
            ]
        );
    }

    /**
     * @Route("/edit/{id}", name="admintags_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, Tags $tag): Response
    {
        return $this->crudEditAction(
            $request,
            [
                'form'       => TagsType::class,
                'entity'     => $tag,
                'url_index'  => 'admintags_index',
                'url_edit'   => 'admintags_edit',
                'url_delete' => 'admintags_delete',
                'title'      => 'Edit tag',
            ]
        );
    }

    /**
     * @Route("/delete", name="admintags_delete", methods={"DELETE"})
     */
    public function delete(Request $request, TagsRepository $repository): Response
    {
        return $this->crudActionDelete($request, $repository, 'admintags_index');
    }
}
