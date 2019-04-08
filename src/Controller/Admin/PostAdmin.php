<?php

namespace Labstag\Controller\Admin;

use Labstag\Entity\Post;
use Labstag\Form\Admin\PostType;
use Labstag\Lib\AdminAbstractControllerLib;
use Labstag\Repository\PostRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin/post")
 */
class PostAdmin extends AdminAbstractControllerLib
{
    /**
     * @Route("/", name="adminpost_index", methods={"GET"})
     */
    public function index(PostRepository $postRepository): Response
    {
        $posts = $postRepository->findAll();
        $this->paginator($posts);

        return $this->twig('admin/post/index.html.twig');
    }

    /**
     * @Route("/new", name="adminpost_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $post = new Post();
        $form = $this->createForm(PostType::class, $post);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($post);
            $entityManager->flush();

            return $this->redirectToRoute('post_index');
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
     * @Route("/{id}/edit", name="adminpost_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Post $post): Response
    {
        $form = $this->createForm(PostType::class, $post);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute(
                'post_index',
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
     * @Route("/{id}", name="adminpost_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Post $post): Response
    {
        return $this->actionDelete($request, $post, 'post_index');
    }
}
