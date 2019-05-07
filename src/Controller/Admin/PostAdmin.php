<?php

namespace Labstag\Controller\Admin;

use Labstag\Entity\Category;
use Labstag\Entity\Post;
use Labstag\Entity\Tags;
use Labstag\Form\Admin\CategoryType;
use Labstag\Form\Admin\PostType;
use Labstag\Form\Admin\TagsType;
use Labstag\Lib\AdminControllerLib;
use Labstag\Repository\CategoryRepository;
use Labstag\Repository\PostRepository;
use Labstag\Repository\TagsRepository;
use Symfony\Component\HttpFoundation\JsonResponse;
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
    public function index(PostRepository $postRepository, CategoryRepository $categoryRepository): Response
    {
        $total     = count($postRepository->findAll());
        $datatable = [
            'Name'      => ['field' => 'name'],
            'User'      => [
                'field'     => 'refuser',
                'formatter' => 'dataFormatter',
            ],
            'Categorie' => [
                'field'     => 'refcategory',
                'formatter' => 'dataFormatter',
            ],
            'File'      => [
                'field'     => 'file',
                'formatter' => 'imageFormatter',
            ],
            'Enable'    => [
                'field'     => 'enable',
                'formatter' => 'enableFormatter',
                'url'       => $this->generateUrl('adminpost_enable'),
            ],
            'CreatedAt' => [
                'field'     => 'createdAt',
                'formatter' => 'dateFormatter',
            ],
            'UpdatedAt' => [
                'field'     => 'updatedAt',
                'formatter' => 'dateFormatter',
            ],
        ];
        $data      = [
            'title'      => 'Post list',
            'total'      => $total,
            'datatable'  => $datatable,
            'api'        => 'api_posts_get_collection',
            'url_delete' => 'adminpost_delete',
            'url_edit'   => 'adminpost_edit',
        ];

        $categories = $categoryRepository->findAll();
        if (count($categories)) {
            $data['url_new'] = 'adminpost_new';
        } else {
            $this->addFlash('warning', 'Vous ne pouvez pas créer de post sans créer de catégories');
        }

        return $this->crudListAction($data);
    }

    /**
     * @Route("/enable", name="adminpost_enable")
     */
    public function enable(Request $request, PostRepository $repository): JsonResponse
    {
        return $this->crudEnableAction($request, $repository, 'setEnable');
    }

    /**
     * @Route("/new", name="adminpost_new", methods={"GET", "POST"})
     */
    public function new(Request $request, CategoryRepository $repository): Response
    {
        $categories = $repository->findAll();
        if (0 == count($categories)) {
            throw new HttpException(403, 'Vous ne pouvez pas créer de post sans créer de catégories');
        }

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
    public function edit(Request $request, Post $post, CategoryRepository $repository): Response
    {
        $categories = $repository->findAll();
        if (0 == count($categories)) {
            throw new HttpException(403, 'Vous ne pouvez pas créer de post sans créer de catégories');
        }

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
        return $this->crudDeleteAction($request, $repository, 'adminpost_index');
    }

    /**
     * @Route("/category/", name="adminpostcategory_index", methods={"GET"})
     */
    public function indexCategory(CategoryRepository $repository): Response
    {
        $total     = count($repository->findAll());
        $datatable = [
            'Name'      => ['field' => 'name'],
            'Posts'     => [
                'field'     => 'posts',
                'formatter' => 'dataTotalFormatter',
            ],
            'CreatedAt' => [
                'field'     => 'createdAt',
                'formatter' => 'dateFormatter',
            ],
            'UpdatedAt' => [
                'field'     => 'updatedAt',
                'formatter' => 'dateFormatter',
            ],
        ];
        $data      = [
            'title'      => 'Category list',
            'total'      => $total,
            'datatable'  => $datatable,
            'api'        => 'api_categories_get_collection',
            'url_new'    => 'adminpostcategory_new',
            'url_delete' => 'adminpostcategory_delete',
            'url_edit'   => 'adminpostcategory_edit',
        ];

        return $this->crudListAction($data);
    }

    /**
     * @Route("/category/new", name="adminpostcategory_new", methods={"GET", "POST"})
     */
    public function newCategory(Request $request): Response
    {
        return $this->crudNewAction(
            $request,
            [
                'entity'    => new Category(),
                'form'      => CategoryType::class,
                'url_edit'  => 'adminpostcategory_edit',
                'url_index' => 'adminpostcategory_index',
                'title'     => 'Add new categorie',
            ]
        );
    }

    /**
     * @Route("/category/edit/{id}", name="adminpostcategory_edit", methods={"GET", "POST"})
     */
    public function editCategory(Request $request, Category $category): Response
    {
        return $this->crudEditAction(
            $request,
            [
                'form'       => CategoryType::class,
                'entity'     => $category,
                'url_index'  => 'adminpostcategory_index',
                'url_edit'   => 'adminpostcategory_edit',
                'url_delete' => 'adminpostcategory_delete',
                'title'      => 'Edit categorie',
            ]
        );
    }

    /**
     * @Route("/category/", name="adminpostcategory_delete", methods={"DELETE"})
     */
    public function deleteCategory(Request $request, CategoryRepository $repository): JsonResponse
    {
        return $this->crudDeleteAction($request, $repository, 'adminpostcategory_index');
    }

    /**
     * @Route("/tags/", name="adminposttags_index", methods={"GET"})
     */
    public function indexTags(TagsRepository $repository): Response
    {
        $total     = count($repository->findAll());
        $datatable = [
            'Name'      => ['field' => 'name'],
            'Posts'     => [
                'field'     => 'posts',
                'formatter' => 'dataTotalFormatter',
            ],
            'CreatedAt' => [
                'field'     => 'createdAt',
                'formatter' => 'dateFormatter',
            ],
            'UpdatedAt' => [
                'field'     => 'updatedAt',
                'formatter' => 'dateFormatter',
            ],
        ];
        $data      = [
            'title'      => 'Tags list',
            'total'      => $total,
            'datatable'  => $datatable,
            'api'        => 'api_tags_get_collection',
            'url_new'    => 'adminposttags_new',
            'url_delete' => 'adminposttags_delete',
            'url_edit'   => 'adminposttags_edit',
        ];

        return $this->crudListAction($data);
    }

    /**
     * @Route("/tags/new", name="adminposttags_new", methods={"GET", "POST"})
     */
    public function newTags(Request $request): Response
    {
        return $this->crudNewAction(
            $request,
            [
                'entity'    => new Tags(),
                'form'      => TagsType::class,
                'url_edit'  => 'adminposttags_edit',
                'url_index' => 'adminposttags_index',
                'title'     => 'Add new tag',
            ]
        );
    }

    /**
     * @Route("/tags/edit/{id}", name="adminposttags_edit", methods={"GET", "POST"})
     */
    public function editTags(Request $request, Tags $tag): Response
    {
        return $this->crudEditAction(
            $request,
            [
                'form'       => TagsType::class,
                'entity'     => $tag,
                'url_index'  => 'adminposttags_index',
                'url_edit'   => 'adminposttags_edit',
                'url_delete' => 'adminposttags_delete',
                'title'      => 'Edit tag',
            ]
        );
    }

    /**
     * @Route("/tags/", name="adminposttags_delete", methods={"DELETE"})
     */
    public function deleteTags(Request $request, TagsRepository $repository): JsonResponse
    {
        return $this->crudDeleteAction($request, $repository, 'adminposttags_index');
    }
}
