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
     * @param string $_format
     */
    public function trash(FormbuilderRepository $repository, $_format)
    {
        return $this->trashAction($repository, $_format);
    }

    /**
     * @Route("/api/formbuilders/trash.{_format}", name="api_formbuildertrashdelete", methods={"DELETE"})
     *
     * @param string $_format
     */
    public function delete(FormbuilderRepository $repository, $_format)
    {
        return $this->deleteAction($repository, $_format);
    }

    /**
     * @Route("/api/formbuilders/restore.{_format}", name="api_formbuilderrestore", methods={"POST"})
     *
     * @param string $_format
     */
    public function restore(FormbuilderRepository $repository, $_format)
    {
        return $this->restoreAction($repository, $_format);
    }

    /**
     * @Route("/api/formbuilders/empty.{_format}", name="api_formbuilderempty", methods={"POST"})
     *
     * @param string $_format
     */
    public function vider(FormbuilderRepository $repository, $_format)
    {
        return $this->emptyAction($repository, $_format);
    }
}
