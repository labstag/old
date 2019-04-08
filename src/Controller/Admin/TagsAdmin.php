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
        return $this->crudNewAction(
            $request,
            [
                'entity'    => new Tags(),
                'form'      => TagsType::class,
                'url_edit'  => 'admintag_edit',
                'url_index' => 'admintag_index',
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
                'form'      => TagsType::class,
                'entity'    => $tag,
                'url_index' => 'admintag_index',
                'url_edit'  => 'admintag_edit',
                'title'     => 'Edit tag',
            ]
        );
    }

    /**
     * @Route("/delete/{id}", name="admintags_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Tags $tag): Response
    {
        return $this->crudActionDelete($request, $tag, 'admintags_index');
    }
}
