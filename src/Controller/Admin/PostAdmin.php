<?php

namespace Labstag\Controller\Admin;

use Labstag\Entity\Post;
use Labstag\Form\Admin\PostType;
use Labstag\Lib\AdminControllerLib;
use Labstag\Repository\PostRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;

/**
 * @Route("/admin/post")
 */
class PostAdmin extends AdminControllerLib
{
    /**
     * @Route("/", name="adminpost_index", methods={"GET"})
     */
    public function index(): Response
    {
        $datatable = [
            'Name'      => [
                'field'    => 'name',
                'sortable' => true,
                'valign'   => 'top',
            ],
            'File'      => [
                'field'     => 'file',
                'sortable'  => true,
                'formatter' => 'imageFormatter',
                'valign'    => 'top',
                'align'     => 'center',
            ],
            'Enable'    => [
                'field'     => 'enable',
                'sortable'  => true,
                'valign'    => 'top',
            ],
            'CreatedAt' => [
                'field'     => 'createdAt',
                'sortable'  => true,
                'formatter' => 'dateFormatter',
                'valign'    => 'top',
            ],
            'UpdatedAt' => [
                'field'     => 'updatedAt',
                'sortable'  => true,
                'formatter' => 'dateFormatter',
                'valign'    => 'top',
            ],
        ];
        $data      = [
            'title'      => 'Post index',
            'datatable'  => $datatable,
            'api'        => 'api_posts_get_collection',
            'url_new'    => 'adminpost_new',
            'url_delete' => 'adminpost_delete',
            'url_edit'   => 'adminpost_edit',
        ];

        return $this->crudListAction($data);
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
                'form'       => PostType::class,
                'entity'     => $post,
                'url_index'  => 'adminpost_index',
                'url_edit'   => 'adminpost_edit',
                'url_delete' => 'adminpost_delete',
                'title'      => 'Edit post',
            ]
        );
    }

    /**
     * @Route("/", name="adminpost_delete", methods={"DELETE"})
     */
    public function delete(Request $request, PostRepository $repository): JsonResponse
    {
        return $this->crudActionDelete($request, $repository, 'adminpost_index');
    }
}
