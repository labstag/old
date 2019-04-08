<?php

namespace Labstag\Controller\Admin;

use Labstag\Entity\Tags;
use Labstag\Form\Admin\TagsType;
use Labstag\Lib\AdminAbstractControllerLib;
use Labstag\Repository\TagsRepository;
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
        $tags = $tagsRepository->findAll();
        $this->paginator($tags);

        return $this->twig('admin/tags/index.html.twig');
    }

    /**
     * @Route("/new", name="admintags_new", methods={"GET", "POST"})
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

            return $this->redirectToRoute('admintags_index');
        }

        return $this->showForm(
            [
                'entity'   => $tag,
                'title'    => 'Add new tag',
                'url_back' => 'admintag_index',
                'form'     => $form->createView(),
            ]
        );
    }

    /**
     * @Route("/{id}/edit", name="admintags_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, Tags $tag): Response
    {
        $form = $this->createForm(TagsType::class, $tag);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectForm(
                [
                    'url'    => 'admintag_edit',
                    'entity' => $tag,
                ]
            );
        }

        return $this->showForm(
            [
                'entity'   => $tag,
                'title'    => 'Edit tag',
                'url_back' => 'admintags_index',
                'form'     => $form->createView(),
            ]
        );
    }

    /**
     * @Route("/{id}", name="admintags_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Tags $tag): Response
    {
        return $this->actionDelete($request, $tag, 'admintags_index');
    }
}
