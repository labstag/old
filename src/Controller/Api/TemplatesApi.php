<?php

namespace Labstag\Controller\Api;

use Labstag\Lib\ApiControllerLib;
use Labstag\Repository\TemplatesRepository;
use Symfony\Component\Routing\Annotation\Route;

class TemplatesApi extends ApiControllerLib
{
    /**
     * @Route("/api/templates/trash.{_format}", name="api_templatestrash")
     *
     * @param string $_format
     */
    public function trash(TemplatesRepository $repository, $_format)
    {
        return $this->trashAction($repository, $_format);
    }

    /**
     * @Route("/api/templates/trash.{_format}", name="api_templatestrashdelete", methods={"DELETE"})
     *
     * @param string $_format
     */
    public function delete(TemplatesRepository $repository, $_format)
    {
        return $this->deleteAction($repository, $_format);
    }

    /**
     * @Route("/api/templates/restore.{_format}", name="api_templatesrestore", methods={"POST"})
     *
     * @param string $_format
     */
    public function restore(TemplatesRepository $repository, $_format)
    {
        return $this->restoreAction($repository, $_format);
    }

    /**
     * @Route("/api/templates/empty.{_format}", name="api_templatesempty", methods={"POST"})
     *
     * @param string $_format
     */
    public function vider(TemplatesRepository $repository, $_format)
    {
        return $this->emptyAction($repository, $_format);
    }
}
