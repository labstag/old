<?php

namespace Labstag\Controller\Admin;

use Labstag\Entity\Category;
use Labstag\Lib\AdminControllerLib;
use Labstag\Form\Admin\CategoryType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Labstag\Repository\CategoryRepository;

/**
 * @Route("/admin/category")
 */
class CategoryAdmin extends AdminControllerLib
{
    /**
     * @Route("/", name="admincategory_index", methods={"GET"})
     */
    public function index(): Response
    {
        $datatable = [
            'Name'      => [
                'field'    => 'name',
                'sortable' => true,
            ],
            'CreatedAt' => [
                'field'     => 'createdAt',
                'sortable'  => true,
                'formatter' => 'dateFormatter',
            ],
            'UpdatedAt' => [
                'field'     => 'updatedAt',
                'sortable'  => true,
                'formatter' => 'dateFormatter',
            ],
        ];
        $data      = [
            'title'      => 'Category index',
            'datatable'  => $datatable,
            'api'        => 'api_categories_get_collection',
            'url_new'    => 'admincategory_new',
            'url_delete' => 'admincategory_delete',
            'url_edit'   => 'admincategory_edit',
        ];
        return $this->crudListAction($data);
    }

    /**
     * @Route("/new", name="admincategory_new", methods={"GET", "POST"})
     */
    public function new(Request $request): Response
    {
        return $this->crudNewAction(
            $request,
            [
                'entity'    => new Category(),
                'form'      => CategoryType::class,
                'url_edit'  => 'admincategory_edit',
                'url_index' => 'admincategory_index',
                'title'     => 'Add new categorie',
            ]
        );
    }

    /**
     * @Route("/edit/{id}", name="admincategory_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, Category $category): Response
    {
        return $this->crudEditAction(
            $request,
            [
                'form'      => CategoryType::class,
                'entity'    => $category,
                'url_index' => 'admincategory_index',
                'url_edit'  => 'admincategory_edit',
                'url_delete'  => 'admincategory_delete',
                'title'     => 'Edit categorie',
            ]
        );
    }

    /**
     * @Route("/delete", name="admincategory_delete", methods={"DELETE"})
     */
    public function delete(Request $request, CategoryRepository $repository): Response
    {
        return $this->crudActionDelete($request, $repository, 'admincategory_index');
    }
}
