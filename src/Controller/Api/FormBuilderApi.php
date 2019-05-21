<?php

namespace Labstag\Controller\Api;

use Labstag\Lib\ApiControllerLib;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Labstag\Repository\FormbuilderRepository;

class FormBuilderApi extends ApiControllerLib
{
    /**
     * @Route("/api/formbuilders/trash.{_format}", name="api_formbuildertrash")
     */
    public function trash(FormbuilderRepository $repository, $_format)
    {
        return $this->trashAction($repository, $_format);
    }

    /**
     * @Route("/api/formbuilders/trash.{_format}", name="api_formbuildertrashdelete", methods={"DELETE"})
     */
    public function delete(FormbuilderRepository $repository, $_format)
    {
        return $this->deleteAction($repository, $_format);
    }

    /**
     * @Route("/api/formbuilders/restore.{_format}", name="api_formbuilderrestore", methods={"POST"})
     */
    public function restore(FormbuilderRepository $repository, $_format)
    {
        return $this->restoreAction($repository, $_format);
    }

    /**
     * @Route("/api/formbuilders/empty.{_format}", name="api_formbuilderempty", methods={"POST"})
     */
    public function empty(FormbuilderRepository $repository, $_format)
    {
        return $this->emptyAction($repository, $_format);
    }
}
