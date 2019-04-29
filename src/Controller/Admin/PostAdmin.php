<?php

namespace Labstag\Controller\Admin;

use Labstag\Entity\Post;
use Labstag\Form\Admin\PostType;
use Labstag\Lib\AdminControllerLib;
use Labstag\Repository\PostRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin/post")
 */
class PostAdmin extends AdminControllerLib
{
    /**
     * @Route("/", name="adminpost_index", methods={"GET"})
     */
    public function index(PostRepository $postRepository): Response
    {
        $this->crudListAction($postRepository);

        return $this->twig('admin/post/index.html.twig');
    }

    /**
     * @Route("/new", name="adminpost_new", methods={"GET", "POST"})
     */
    public function new(Request $request): Response
    {
        return $this->crudNewAction(
            $request,
            [
                'entity'    => new Post(),
                'form'      => PostType::class,
                'url_edit'  => 'adminpost_edit',
                'url_index' => 'adminpost_index',
                'title'     => 'Add new post',
            ]
        );
    }

    /**
     * @Route("/edit/{id}", name="adminpost_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, Post $post): Response
    {
        return $this->crudEditAction(
            $request,
            [
                'form'      => PostType::class,
                'entity'    => $post,
                'url_index' => 'adminpost_index',
                'url_edit'  => 'adminpost_edit',
                'title'     => 'Edit post',
            ]
        );
    }

    /**
     * @Route("/delete/{id}", name="adminpost_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Post $post): Response
    {
        return $this->crudActionDelete($request, $post, 'adminpost_index');
    }
}
