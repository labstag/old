<?php

namespace Labstag\Controller\Api;

use Labstag\Entity\Tags;
use Labstag\Lib\ApiControllerLib;
use Labstag\Repository\TagsRepository;
use Labstag\Handler\TagsPublishingHandler;
use Symfony\Component\Routing\Annotation\Route;

class TagsApi extends ApiControllerLib
{

    public function __construct(TagsPublishingHandler $tagsPublishingHandler)
    {
        $this->tagsPublishingHandler = $tagsPublishingHandler;
    }

    public function __invoke(Tags $data): Tags
    {
        $this->tagsPublishingHandler->handle($data);

        return $data;
    }
    /**
     * @Route("/api/tags/trash", name="api_tagstrash")
     *
     * @param string $_format
     */
    public function trash(TagsRepository $repository)
    {
        return $this->trashAction($repository);
    }

    /**
     * @Route("/api/tags/trash", name="api_tagstrashdelete", methods={"DELETE"})
     *
     * @param string $_format
     */
    public function delete(TagsRepository $repository)
    {
        return $this->deleteAction($repository);
    }

    /**
     * @Route("/api/tags/restore", name="api_tagsrestore", methods={"Tags"})
     *
     * @param string $_format
     */
    public function restore(TagsRepository $repository)
    {
        return $this->restoreAction($repository);
    }

    /**
     * @Route("/api/tags/empty", name="api_tagsempty", methods={"Tags"})
     *
     * @param string $_format
     */
    public function vider(TagsRepository $repository)
    {
        return $this->emptyAction($repository);
    }
}
