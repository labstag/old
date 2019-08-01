<?php

namespace Labstag\Controller\Api;

use Labstag\Lib\ApiControllerLib;
use Labstag\Repository\FormbuilderRepository;
use Symfony\Component\Routing\Annotation\Route;

class FormBuilderApi extends ApiControllerLib
{
    /**
     * @Route("/api/formbuilders/trash.{_format}", name="api_formbuildertrash")
     *
     * @param string $format
     */
    public function trash(FormbuilderRepository $repository, $format)
    {
        return $this->trashAction($repository, $format);
    }

    /**
     * @Route("/api/formbuilders/trash.{_format}", name="api_formbuildertrashdelete", methods={"DELETE"})
     *
     * @param string $format
     */
    public function delete(FormbuilderRepository $repository, $format)
    {
        return $this->deleteAction($repository, $format);
    }

    /**
     * @Route("/api/formbuilders/restore.{_format}", name="api_formbuilderrestore", methods={"POST"})
     *
     * @param string $format
     */
    public function restore(FormbuilderRepository $repository, $format)
    {
        return $this->restoreAction($repository, $format);
    }

    /**
     * @Route("/api/formbuilders/empty.{_format}", name="api_formbuilderempty", methods={"POST"})
     *
     * @param string $format
     */
    public function vider(FormbuilderRepository $repository, $format)
    {
        return $this->emptyAction($repository, $format);
    }
}
