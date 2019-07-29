<?php

namespace Labstag\Controller\Api;

use Labstag\Lib\ApiControllerLib;
use Labstag\Repository\UserRepository;
use Symfony\Component\Routing\Annotation\Route;

class UserApi extends ApiControllerLib
{
    /**
     * @Route("/api/user/trash.{_format}", name="api_usertrash")
     *
     * @param string $_format
     */
    public function trash(UserRepository $repository, $_format)
    {
        return $this->trashAction($repository, $_format);
    }

    /**
     * @Route("/api/user/trash.{_format}", name="api_usertrashdelete", methods={"DELETE"})
     *
     * @param string $_format
     */
    public function delete(UserRepository $repository, $_format)
    {
        return $this->deleteAction($repository, $_format);
    }

    /**
     * @Route("/api/user/restore.{_format}", name="api_userrestore", methods={"POST"})
     *
     * @param string $_format
     */
    public function restore(UserRepository $repository, $_format)
    {
        return $this->restoreAction($repository, $_format);
    }

    /**
     * @Route("/api/user/empty.{_format}", name="api_userempty", methods={"POST"})
     *
     * @param string $_format
     */
    public function empty(UserRepository $repository, $_format)
    {
        return $this->emptyAction($repository, $_format);
    }
}
