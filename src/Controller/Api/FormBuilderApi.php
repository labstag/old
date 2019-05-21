<?php

namespace Labstag\Controller\Api;

use Labstag\Lib\ApiControllerLib;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

class FormBuilderApi extends ApiControllerLib
{
    /**
     * @Route("/api/formbuilder/trash", name="api_formbuildertrash")
     */
    public function trash(): JsonResponse
    {
        return $this->trashAction();
    }

    /**
     * @Route("/api/formbuilder/trash", name="api_formbuildertrashdelete", methods={"DELETE"})
     */
    public function delete(): JsonResponse
    {
        return $this->deleteAction();
    }

    /**
     * @Route("/api/formbuilder/restore", name="api_formbuilderrestore", methods={"POST"})
     */
    public function restore(): JsonResponse
    {
        return $this->restoreAction();
    }

    /**
     * @Route("/api/formbuilder/empty", name="api_formbuilderempty", methods={"POST"})
     */
    public function empty(): JsonResponse
    {
        return $this->emptyAction();
    }
}
