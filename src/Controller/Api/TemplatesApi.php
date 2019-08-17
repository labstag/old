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
     * @param string $format
     */
    public function trash(TemplatesRepository $repository, $format)
    {
        return $this->trashAction($repository, $format);
    }

    /**
     * @Route("/api/templates/trash.{_format}", name="api_templatestrashdelete", methods={"DELETE"})
     *
     * @param string $format
     */
    public function delete(TemplatesRepository $repository, $format)
    {
        return $this->deleteAction($repository, $format);
    }

    /**
     * @Route("/api/templates/restore.{_format}", name="api_templatesrestore", methods={"POST"})
     *
     * @param string $format
     */
    public function restore(TemplatesRepository $repository, $format)
    {
        return $this->restoreAction($repository, $format);
    }

    /**
     * @Route("/api/templates/empty.{_format}", name="api_templatesempty", methods={"POST"})
     *
     * @param string $format
     */
    public function vider(TemplatesRepository $repository, $format)
    {
        return $this->emptyAction($repository, $format);
    }
}
