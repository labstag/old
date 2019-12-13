<?php

namespace Labstag\Controller\Api;

use Knp\Component\Pager\PaginatorInterface;
use Labstag\Entity\Tags;
use Labstag\Handler\TagsPublishingHandler;
use Labstag\Lib\ApiControllerLib;
use Labstag\Repository\TagsRepository;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\RouterInterface;

class TagsApi extends ApiControllerLib
{
    public function __construct(
        TagsPublishingHandler $handler,
        ContainerInterface $container,
        PaginatorInterface $paginator,
        RequestStack $requestStack,
        RouterInterface $router
    )
    {
        $this->tagsPublishingHandler = $handler;
    }

    public function __invoke(Tags $data): Tags
    {
        $this->tagsPublishingHandler->handle($data);

        return $data;
    }

    /**
     * @Route("/api/tags/trash", name="api_tagstrash")
     */
    public function trash(TagsRepository $repository)
    {
        return $this->trashAction($repository);
    }

    /**
     * @Route("/api/tags/trash", name="api_tagstrashdelete", methods={"DELETE"})
     */
    public function delete(TagsRepository $repository)
    {
        return $this->deleteAction($repository);
    }

    /**
     * @Route("/api/tags/restore", name="api_tagsrestore", methods={"Tags"})
     */
    public function restore(TagsRepository $repository)
    {
        return $this->restoreAction($repository);
    }

    /**
     * @Route("/api/tags/empty", name="api_tagsempty", methods={"Tags"})
     */
    public function vider(TagsRepository $repository)
    {
        return $this->emptyAction($repository);
    }
}
