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
        $categories = $categoryRepository->findAll();
        $this->paginator($categories);

        return $this->twig('admin/category/index.html.twig');
    }

    /**
     * @Route("/new", name="admincategory_new", methods={"GET", "POST"})
     */
    public function new(Request $request): Response
    {
        $category = new Category();
        $form     = $this->createForm(CategoryType::class, $category);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($category);
            $entityManager->flush();

            return $this->redirectToRoute('category_index');
        }

        return $this->showForm(
            [
                'entity'   => $category,
                'title'    => 'Add new categorie',
                'url_back' => 'admincategorie_index',
                'form'     => $form->createView(),
            ]
        );
    }

    /**
     * @Route("/{id}/edit", name="admincategory_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, Category $category): Response
    {
        $form = $this->createForm(CategoryType::class, $category);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute(
                'category_index',
                [
                    'id' => $category->getId(),
                ]
            );
        }

        return $this->showForm(
            [
                'entity'   => $category,
                'title'    => 'Edit categorie',
                'url_back' => 'admincategory_index',
                'form'     => $form->createView(),
            ]
        );
    }

    /**
     * @Route("/{id}", name="admincategory_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Category $category): Response
    {
        return $this->actionDelete($request, $category, 'admincategory_index');
    }
}
