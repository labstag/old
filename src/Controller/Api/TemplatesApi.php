<?php

namespace Labstag\Controller\Api;

use Labstag\Entity\Templates;
use Labstag\Lib\ApiControllerLib;
use Labstag\Repository\TemplatesRepository;
use Labstag\Handler\TemplatesPublishingHandler;
use Symfony\Component\Routing\Annotation\Route;

class TemplatesApi extends ApiControllerLib
{

    public function __construct(TemplatesPublishingHandler $templatesPublishingHandler)
    {
        $this->templatesPublishingHandler = $templatesPublishingHandler;
    }

    public function __invoke(Templates $data): Templates
    {
        $this->templatesPublishingHandler->handle($data);

        return $data;
    }
    /**
     * @Route("/api/templates/trash", name="api_templatestrash")
     *
     * @param string $_format
     */
    public function trash(TemplatesRepository $repository)
    {
        return $this->trashAction($repository);
    }

    /**
     * @Route("/api/templates/trash", name="api_templatestrashdelete", methods={"DELETE"})
     *
     * @param string $_format
     */
    public function delete(TemplatesRepository $repository)
    {
        return $this->deleteAction($repository);
    }

    /**
     * @Route("/api/templates/restore", name="api_templatesrestore", methods={"POST"})
     *
     * @param string $_format
     */
    public function restore(TemplatesRepository $repository)
    {
        return $this->restoreAction($repository);
    }

    /**
     * @Route("/api/templates/empty", name="api_templatesempty", methods={"POST"})
     *
     * @param string $_format
     */
    public function vider(TemplatesRepository $repository)
    {
        return $this->emptyAction($repository);
    }
}
