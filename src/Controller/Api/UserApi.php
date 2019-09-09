<?php

namespace Labstag\Controller\Api;

use Labstag\Lib\ApiControllerLib;
use Labstag\Repository\UserRepository;
use Symfony\Component\Routing\Annotation\Route;

class UserApi extends ApiControllerLib
{
    /**
     * @Route("/api/users/trash.{_format}", name="api_usertrash")
     *
     * @param string $format
     */
    public function trash(UserRepository $repository, $format)
    {
        return $this->trashAction($repository, $format);
    }

    /**
     * @Route("/api/users/trash.{_format}", name="api_usertrashdelete", methods={"DELETE"})
     *
     * @param string $format
     */
    public function delete(UserRepository $repository, $format)
    {
        return $this->deleteAction($repository, $format);
    }

    /**
     * @Route("/api/users/restore.{_format}", name="api_userrestore", methods={"POST"})
     *
     * @param string $format
     */
    public function restore(UserRepository $repository, $format)
    {
        return $this->restoreAction($repository, $format);
    }

    /**
     * @Route("/api/users/empty.{_format}", name="api_userempty", methods={"POST"})
     *
     * @param string $format
     */
    public function vider(UserRepository $repository, $format)
    {
        return $this->emptyAction($repository, $format);
    }
}
