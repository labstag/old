<?php

namespace App\Controller\Api;

use App\Entity\User;
use App\Form\Admin\UserType;
use App\Action\UserAction;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class UserController extends AbstractController
{

    /**
     * @Route("/api/user/check", name="api_checkuser")
     */
    public function check(Request $request)
    {
        $get  = $request->query->all();
        $post = $request->request->all();
        return $this->json(
            [
                'get'  => $get,
                'post' => $post,
            ]
        );
    }
}
