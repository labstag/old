<?php

namespace App\Controller\Admin;

use App\Entity\Tags;
use App\Form\Admin\TagsType;
use App\Lib\AdminAbstractControllerLib;
use App\Repository\TagsRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin/tags")
 */
class TagsAdmin extends AdminAbstractControllerLib
{
    /**
     * @Route("/", name="admintags_index", methods={"GET"})
     */
    public function index(TagsRepository $tagsRepository): Response
    {
        return $this->render(
            'admin/tags/index.html.twig',
            [
                'tags' => $tagsRepository->findAll(),
            ]
        );
    }

    /**
     * @Route("/new", name="admintags_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $tag  = new Tags();
        $form = $this->createForm(TagsType::class, $tag);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($tag);
            $entityManager->flush();

            return $this->redirectToRoute('tags_index');
        }

        return $this->render(
            'admin/tags/new.html.twig',
            [
                'tag'  => $tag,
                'form' => $form->createView(),
            ]
        );
    }

    /**
     * @Route("/{id}/edit", name="admintags_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Tags $tag): Response
    {
        $form = $this->createForm(TagsType::class, $tag);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute(
                'tags_index',
                [
                    'id' => $tag->getId(),
                ]
            );
        }

        return $this->render(
            'admin/tags/edit.html.twig',
            [
                'tag'  => $tag,
                'form' => $form->createView(),
            ]
        );
    }

    /**
     * @Route("/{id}", name="admintags_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Tags $tag): Response
    {
        $token = $request->request->get('_token');
        $uuid  = $tag->getId();
        if ($this->isCsrfTokenValid('delete'.$uuid, $token)) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($tag);
            $entityManager->flush();
        }

        return $this->redirectToRoute('tags_index');
    }
}
