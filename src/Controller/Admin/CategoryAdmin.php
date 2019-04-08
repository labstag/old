<?php

namespace Labstag\Controller\Admin;

use Labstag\Entity\Category;
use Labstag\Form\Admin\CategoryType;
use Labstag\Lib\AdminAbstractControllerLib;
use Labstag\Repository\CategoryRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin/category")
 */
class CategoryAdmin extends AdminAbstractControllerLib
{
    /**
     * @Route("/", name="admincategory_index", methods={"GET"})
     */
    public function index(CategoryRepository $categoryRepository): Response
    {
        $this->crudListAction($categoryRepository);

        return $this->twig('admin/category/index.html.twig');
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
                'title'     => 'Edit categorie',
            ]
        );
    }

    /**
     * @Route("/delete/{id}", name="admincategory_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Category $category): Response
    {
        return $this->crudActionDelete($request, $category, 'admincategory_index');
    }
}
