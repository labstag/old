<?php

namespace Labstag\Controller\Api;

use Knp\Component\Pager\PaginatorInterface;
use Labstag\Entity\User;
use Labstag\Handler\UserPublishingHandler;
use Labstag\Lib\ApiControllerLib;
use Labstag\Repository\UserRepository;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\RouterInterface;

class UserApi extends ApiControllerLib
{

    /**
     * @var UserPublishingHandler
     */
    protected $publishingHandler;

    public function __construct(
        UserPublishingHandler $publishingHandler,
        ContainerInterface $container,
        PaginatorInterface $paginator,
        RequestStack $requestStack,
        RouterInterface $router
    )
    {
        parent::__construct($container, $paginator, $requestStack, $router);
        $this->publishingHandler = $publishingHandler;
    }

    public function __invoke(User $data): User
    {
        $this->publishingHandler->handle($data);

        return $data;
    }

    /**
     * @Route("/api/users/trash", name="api_usertrash")
     */
    public function trash(UserRepository $repository)
    {
        return $this->trashAction($repository);
    }

    /**
     * @Route("/api/users/trash", name="api_usertrashdelete", methods={"DELETE"})
     */
    public function delete(UserRepository $repository)
    {
        return $this->deleteAction($repository);
    }

    /**
     * @Route("/api/users/restore", name="api_userrestore", methods={"POST"})
     */
    public function restore(UserRepository $repository)
    {
        return $this->restoreAction($repository);
    }

    /**
     * @Route("/api/users/empty", name="api_userempty", methods={"POST"})
     */
    public function vider(UserRepository $repository)
    {
        return $this->emptyAction($repository);
    }
}
