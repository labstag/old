<?php

namespace Labstag\Controller\Api;

use Labstag\Lib\ApiControllerLib;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

class UserApi extends ApiControllerLib
{
    /**
     * @Route("/api/user/trash", name="api_usertrash")
     */
    public function check(): JsonResponse
    {
        return $this->trashAction();
    }

    /**
     * @Route("/api/user/trash", name="api_usertrashdelete", methods={"DELETE"})
     */
    public function delete(): JsonResponse
    {
        return $this->deleteAction();
    }

    /**
     * @Route("/api/user/restore", name="api_userrestore", methods={"POST"})
     */
    public function restore(): JsonResponse
    {
        return $this->restoreAction();
    }

    /**
     * @Route("/api/user/empty", name="api_userempty", methods={"POST"})
     */
    public function empty(): JsonResponse
    {
        return $this->emptyAction();
    }
}
