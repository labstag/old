<?php

namespace Labstag\Controller\Api;

use Labstag\Lib\ApiControllerLib;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Labstag\Repository\UserRepository;

class UserApi extends ApiControllerLib
{
    /**
     * @Route("/api/user/trash.{_format}", name="api_usertrash")
     */
    public function trash(UserRepository $repository, $_format)
    {
        return $this->trashAction($repository, $_format);
    }

    /**
     * @Route("/api/user/trash.{_format}", name="api_usertrashdelete", methods={"DELETE"})
     */
    public function delete(UserRepository $repository, $_format)
    {
        return $this->deleteAction($repository, $_format);
    }

    /**
     * @Route("/api/user/restore.{_format}", name="api_userrestore", methods={"POST"})
     */
    public function restore(UserRepository $repository, $_format)
    {
        return $this->restoreAction($repository, $_format);
    }

    /**
     * @Route("/api/user/empty.{_format}", name="api_userempty", methods={"POST"})
     */
    public function empty(UserRepository $repository, $_format)
    {
        return $this->emptyAction($repository, $_format);
    }
}