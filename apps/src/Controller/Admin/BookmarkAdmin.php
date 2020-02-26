<?php

namespace Labstag\Controller\Admin;

use DateTime;
use Labstag\Entity\Bookmark;
use Labstag\Entity\Tag;
use Labstag\Form\Admin\BookmarkType;
use Labstag\Form\Admin\TagType;
use Labstag\Lib\AdminControllerLib;
use Labstag\Repository\BookmarkRepository;
use Labstag\Repository\TagRepository;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin/bookmark")
 */
class BookmarkAdmin extends AdminControllerLib
{
    /**
     * @Route("/", name="adminbookmark_list", methods={"GET"})
     * @Route("/trash", name="adminbookmark_trash", methods={"GET"})
     */
    public function list(BookmarkRepository $repository): Response
    {
        $datatable = [
            'Name'      => [
                'field'      => 'name',
                'switchable' => false,
            ],
            'User'      => [
                'field'     => 'refuser',
                'formatter' => 'dataFormatter',
            ],
            'File'      => [
                'field'     => 'file',
                'formatter' => 'imageFormatter',
            ],
            'Enable'    => [
                'field'     => 'enable',
                'formatter' => 'enableFormatter',
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
            'title'           => 'Bookmark list',
            'datatable'       => $datatable,
            'repository'      => $repository,
            'api'             => 'api_bookmarks_get_collection',
            'url_new'         => 'adminbookmark_new',
            'graphql_query'   => [
                'table'  => 'bookmarks',
                'node'   => 'id _id name refuser{id _id username avatar} file enable createdAt updatedAt',
                'params' => '',
            ],
            'url_delete'      => 'adminbookmark_delete',
            'url_deletetrash' => 'adminbookmark_deletetrash',
            'url_trash'       => 'adminbookmark_trash',
            'url_restore'     => 'adminbookmark_restore',
            'url_enable'      => ['enable' => 'adminbookmark_enable'],
            'url_empty'       => 'adminbookmark_empty',
            'url_list'        => 'adminbookmark_list',
            'url_edit'        => 'adminbookmark_edit',
            'url_trashedit'   => 'adminbookmark_trashedit',
        ];

        return $this->crudListAction($data);
    }

    /**
     * @Route("/enable", name="adminbookmark_enable")
     */
    public function enable(BookmarkRepository $repository): JsonResponse
    {
        return $this->crudEnableAction($repository, 'setEnable');
    }

    /**
     * @Route("/new", name="adminbookmark_new", methods={"GET", "POST"})
     */
    public function new(): Response
    {
        return $this->crudNewAction(
            [
                'entity'   => new Bookmark(),
                'form'     => BookmarkType::class,
                'url_edit' => 'adminbookmark_edit',
                'url_list' => 'adminbookmark_list',
                'title'    => 'Add new bookmark',
            ]
        );
    }

    /**
     * @Route("/trashedit/{id}", name="adminbookmark_trashedit", methods={"GET", "POST"})
     *
     * @param mixed $id
     */
    public function trashEdit(BookmarkRepository $repository, $id): Response
    {
        $bookmark = $repository->findOneDateInTrash($id);

        return $this->crudEditAction(
            [
                'form'       => BookmarkType::class,
                'entity'     => $bookmark,
                'url_list'   => 'adminbookmark_trash',
                'url_edit'   => 'adminbookmark_trashedit',
                'url_delete' => 'adminbookmark_deletetrash',
                'title'      => 'Edit bookmark',
            ]
        );
    }

    /**
     * @Route("/edit/{id}", name="adminbookmark_edit", methods={"GET", "POST"})
     */
    public function edit(Bookmark $bookmark): Response
    {
        $bookmark->setUpdatedAt(new DateTime());

        return $this->crudEditAction(
            [
                'form'       => BookmarkType::class,
                'entity'     => $bookmark,
                'url_list'   => 'adminbookmark_list',
                'url_edit'   => 'adminbookmark_edit',
                'url_delete' => 'adminbookmark_delete',
                'title'      => 'Edit bookmark',
            ]
        );
    }

    /**
     * @Route("/empty", name="adminbookmark_empty")
     */
    public function emptyBookmark(BookmarkRepository $repository): JsonResponse
    {
        return $this->crudEmptyAction($repository, 'adminbookmark_list');
    }

    /**
     * @Route("/restore", name="adminbookmark_restore")
     */
    public function restore(BookmarkRepository $repository): JsonResponse
    {
        return $this->crudRestoreAction(
            $repository,
            [
                'url_list'  => 'adminbookmark_list',
                'url_trash' => 'adminbookmark_trash',
            ]
        );
    }

    /**
     * @Route("/", name="adminbookmark_delete", methods={"DELETE"})
     * @Route("/trash", name="adminbookmark_deletetrash", methods={"DELETE"})
     */
    public function delete(BookmarkRepository $repository): JsonResponse
    {
        return $this->crudDeleteAction(
            $repository,
            [
                'url_list'  => 'adminbookmark_list',
                'url_trash' => 'adminbookmark_trash',
            ]
        );
    }

    /**
     * @Route("/tags/", name="adminbookmarktags_list", methods={"GET"})
     * @Route("/tags/trash", name="adminbookmarktags_trash", methods={"GET"})
     */
    public function listTag(TagRepository $repository): Response
    {
        $datatable = [
            'Name'      => [
                'field'      => 'name',
                'switchable' => false,
            ],
            'Bookmarks' => [
                'field'     => 'bookmarks',
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
            'title'           => 'Tag list',
            'datatable'       => $datatable,
            'repository'      => $repository,
            'api'             => 'api_tags_get_collection',
            'api_param'       => [
                'type'      => 'bookmark',
                'temporary' => false,
            ],
            'url_new'         => 'adminbookmarktags_new',
            'graphql_query'   => [
                'table'  => 'tags',
                'node'   => 'id _id name name bookmarks{totalCount} createdAt updatedAt',
                'params' => 'type: "bookmark"',
            ],
            'url_delete'      => 'adminbookmarktags_delete',
            'url_deletetrash' => 'adminbookmarktags_deletetrash',
            'url_trash'       => 'adminbookmarktags_trash',
            'url_restore'     => 'adminbookmarktags_restore',
            'url_empty'       => 'adminbookmarktags_empty',
            'url_list'        => 'adminbookmarktags_list',
            'url_edit'        => 'adminbookmarktags_edit',
            'url_trashedit'   => 'adminbookmarktags_trashedit',
        ];

        return $this->crudListAction($data);
    }

    /**
     * @Route("/tags/new", name="adminbookmarktags_new", methods={"GET", "POST"})
     */
    public function newTag(): Response
    {
        return $this->crudNewAction(
            [
                'entity'   => new Tag(),
                'form'     => TagType::class,
                'url_edit' => 'adminbookmarktags_edit',
                'url_list' => 'adminbookmarktags_list',
                'title'    => 'Add new tag',
            ]
        );
    }

    /**
     * @Route("/tags/trashedit/{id}", name="adminbookmarktags_trashedit", methods={"GET", "POST"})
     *
     * @param mixed $id
     */
    public function trashEditTag(TagRepository $repository, $id): Response
    {
        $tag = $repository->findOneDateInTrash($id);

        return $this->crudEditAction(
            [
                'form'       => TagType::class,
                'entity'     => $tag,
                'url_list'   => 'adminbookmarktags_trash',
                'url_edit'   => 'adminbookmarktags_trashedit',
                'url_delete' => 'adminbookmarktags_deletetrash',
                'title'      => 'Edit tag',
            ]
        );
    }

    /**
     * @Route("/tags/edit/{id}", name="adminbookmarktags_edit", methods={"GET", "POST"})
     */
    public function editTag(Tag $tag): Response
    {
        return $this->crudEditAction(
            [
                'form'       => TagType::class,
                'entity'     => $tag,
                'url_list'   => 'adminbookmarktags_list',
                'url_edit'   => 'adminbookmarktags_edit',
                'url_delete' => 'adminbookmarktags_delete',
                'title'      => 'Edit tag',
            ]
        );
    }

    /**
     * @Route("/tags/empty", name="adminbookmarktags_empty")
     */
    public function emptyTag(TagRepository $repository): JsonResponse
    {
        return $this->crudEmptyAction($repository, 'adminbookmarktags_list');
    }

    /**
     * @Route("/tags/", name="adminbookmarktags_delete", methods={"DELETE"})
     * @Route("/tags/trash", name="adminbookmarktags_deletetrash", methods={"DELETE"})
     */
    public function deleteTag(TagRepository $repository): JsonResponse
    {
        return $this->crudDeleteAction(
            $repository,
            [
                'url_list'  => 'adminbookmarktags_list',
                'url_trash' => 'adminbookmarktags_trash',
            ]
        );
    }

    /**
     * @Route("/tags/restore", name="adminbookmarktags_restore")
     */
    public function restoreTag(TagRepository $repository): JsonResponse
    {
        return $this->crudRestoreAction(
            $repository,
            [
                'url_list'  => 'adminbookmarktags_list',
                'url_trash' => 'adminbookmarktags_trash',
            ]
        );
    }
}
