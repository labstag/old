<?php

namespace Labstag\Controller\Admin;

use Doctrine\ORM\QueryBuilder;
use Labstag\Entity\Tags;
use Labstag\Lib\AdminControllerLib;
use Labstag\Repository\TagsRepository;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin/temporary")
 */
class TemporaryAdmin extends AdminControllerLib
{
    /**
     * @Route("/tags/{type}", name="admintemporary_tags")
     */
    public function tags(TagsRepository $repository, string $type): JsonResponse
    {
        $tabs = [
            'data' => [],
        ];
        $post = $this->request->request->all();
        if (isset($post['req'])) {
            $search = [
                'type' => $type,
                'name' => $post['req'],
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

        /** @var QueryBuilder $data */
        $data    = $repository->findTagsByType($type);
        $query   = $data->getQuery();
        $results = $query->getResult();
        foreach ($results as $row) {
            $tabs['data'][] = [
                'value' => $row->getId(),
                'name'  => $row->getName(),
            ];
        }

        return new JsonResponse($tabs);
    }

    /**
     * @Route("/category", name="admintemporary_category")
     */
    public function category(): JsonResponse
    {
        $data = [];

        return new JsonResponse($data);
    }
}
