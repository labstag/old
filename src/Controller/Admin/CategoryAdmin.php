<?php

namespace App\Controller\Admin;

use App\Entity\Category;
use App\Form\Admin\CategoryType;
use App\Lib\AdminAbstractControllerLib;
use App\Repository\CategoryRepository;
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

        return $this->render('admin/category/index.html.twig');
    }

    /**
     * @Route("/new", name="admincategory_new", methods={"GET","POST"})
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

        return $this->render(
            'admin/category/new.html.twig',
            [
                'category' => $category,
                'form'     => $form->createView(),
            ]
        );
    }

    /**
     * @Route("/{id}/edit", name="admincategory_edit", methods={"GET","POST"})
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

        return $this->render(
            'admin/category/edit.html.twig',
            [
                'category' => $category,
                'form'     => $form->createView(),
            ]
        );
    }

    /**
     * @Route("/{id}", name="admincategory_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Category $category): Response
    {
        $token = $request->request->get('_token');
        $uuid  = $category->getId();
        if ($this->isCsrfTokenValid('delete'.$uuid, $token)) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($category);
            $entityManager->flush();
        }

        return $this->redirectToRoute('category_index');
    }
}
