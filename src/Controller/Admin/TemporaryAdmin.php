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
use Labstag\Repository\CategoryRepository;

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
    public function tags(TagsRepository $repository, string $type): JsonResponse
    {
        $tabs = [
            'data' => []
        ];
        $post = $this->request->request->all();
        if (isset($post['req'])) {
            $search = [
                'type' => $type,
                'name' => $post['req']
            ];
            $tag    = $repository->findOneBy($search);
            if (!$tag) {
                $tags = new Tags();
                $tags->setType($type);
                $tags->setName($post['req']);
                $tags->setTemporary(true);
                $this->persistAndFlush($tags);
            }
        }

        $data    = $repository->findTagsByType($type);
        $query   = $data->getQuery();
        $results = $query->getResult();
        foreach ($results as $row) {
            $tabs['data'][] = [
                'value' => $row->getId(),
                'name'  => $row->getName(),
            ];
        };
        return new JsonResponse($tabs);
    }

    /**
     * @Route("/category", name="admintemporary_category")
     *
     * @return Response
     */
    public function category(CategoryRepository $repository): JsonResponse
    {
        // $repository->find
        $data = [];
        return new JsonResponse($data);
    }
}
