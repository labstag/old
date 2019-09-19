<?php

namespace Labstag\Controller\Api;

use Labstag\Entity\User;
use Labstag\Lib\ApiControllerLib;
use Labstag\Repository\UserRepository;
use Labstag\Handler\UserPublishingHandler;
use Symfony\Component\Routing\Annotation\Route;

class UserApi extends ApiControllerLib
{

    public function __construct(UserPublishingHandler $userPublishingHandler)
    {
        $this->userPublishingHandler = $userPublishingHandler;
    }

    public function __invoke(User $data): User
    {
        $this->userPublishingHandler->handle($data);

        return $data;
    }
    /**
     * @Route("/api/users/trash", name="api_usertrash")
     *
     * @param string $_format
     */
    public function trash(UserRepository $repository)
    {
        return $this->trashAction($repository);
    }

    /**
     * @Route("/api/users/trash", name="api_usertrashdelete", methods={"DELETE"})
     *
     * @param string $_format
     */
    public function delete(UserRepository $repository)
    {
        return $this->deleteAction($repository);
    }

    /**
     * @Route("/api/users/restore", name="api_userrestore", methods={"POST"})
     *
     * @param string $_format
     */
    public function restore(UserRepository $repository)
    {
        return $this->restoreAction($repository);
    }

    /**
     * @Route("/api/users/empty", name="api_userempty", methods={"POST"})
     *
     * @param string $_format
     */
    public function vider(UserRepository $repository)
    {
        return $this->emptyAction($repository);
    }
}
