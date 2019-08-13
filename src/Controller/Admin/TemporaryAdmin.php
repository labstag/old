<?php

namespace Labstag\Controller\Admin;

use Labstag\Entity\Bookmark;
use Labstag\Entity\Tags;
use Labstag\Form\Admin\BookmarkType;
use Labstag\Form\Admin\TagsType;
use Labstag\Lib\AdminControllerLib;
use Labstag\Repository\BookmarkRepository;
use Labstag\Repository\TagsRepository;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin/temporary")
 */
class TemporaryAdmin extends AdminControllerLib
{
    /**
     * @Route("/tags/{type}", name="admintemporary_tags")
     *
     * @return Response
     */
    public function tags($type): JsonResponse
    {
        $tags = new Tags();
        $tags->setType($type);
        $post = $this->request->request->all();
        $data = [];
        return new JsonResponse($post);
    }

    /**
     * @Route("/category", name="admintemporary_category")
     *
     * @return Response
     */
    public function category(): JsonResponse
    {
        $data = [];
        return new JsonResponse($data);
    }
}
