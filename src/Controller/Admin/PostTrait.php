<?php

namespace App\Controller\Admin;

use App\Entity\Post;
use App\Form\Admin\PostType;
use App\Repository\PostRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Lib\AbstractControllerLib;

trait PostTrait
{
    /**
     * @Route("/post/", name="adminpost_index", methods={"GET"})
     */
    public function postList(PostRepository $postRepository): Response
    {
        $posts      = $postRepository->findAll();
        $pagination = $this->paginator($posts);
        return $this->twig(
            'admin/post/index.html.twig',
            [
                'pagination' => $pagination,
            ]
        );
    }

    /**
     * @Route("/post/new", name="adminpost_new", methods={"GET","POST"})
     */
    public function postNew(Request $request): Response
    {
        $post = new Post();
        $form = $this->createForm(PostType::class, $post);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($post);
            $entityManager->flush();

            return $this->redirectToRoute('adminpost_index');
        }

        return $this->twig(
            'admin/post/new.html.twig',
            [
                'post' => $post,
                'form' => $form->createView(),
            ]
        );
    }

    /**
     * @Route("/post/{id}", name="adminpost_show", methods={"GET"})
     */
    public function postShow(Post $post): Response
    {
        return $this->twig(
            'admin/post/show.html.twig',
            [
                'post' => $post,
            ]
        );
    }

    /**
     * @Route("/post/{id}/edit", name="adminpost_edit", methods={"GET","POST"})
     */
    public function postEdit(Request $request, Post $post): Response
    {
        $form = $this->createForm(PostType::class, $post);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute(
                'adminpost_index',
                [
                    'id' => $post->getId(),
                ]
            );
        }

        return $this->twig(
            'admin/post/edit.html.twig',
            [
                'post' => $post,
                'form' => $form->createView(),
            ]
        );
    }

    /**
     * @Route("/post/{id}", name="adminpost_delete", methods={"DELETE"})
     */
    public function postDelete(Request $request, Post $post): Response
    {
        if ($this->isCsrfTokenValid('delete'.$post->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($post);
            $entityManager->flush();
        }

        return $this->redirectToRoute('adminpost_index');
    }
}
