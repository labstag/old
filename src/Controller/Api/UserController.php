<?php

namespace App\Controller\Api;

use App\Entity\User;
use App\Form\Admin\UserType;
use App\Action\UserAction;
use App\Repository\UserRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Lib\AbstractControllerLib;

class UserController extends AbstractControllerLib
{

    /**
     * @Route("/api/user/check", name="api_checkuser")
     */
    public function check(Request $request)
    {
        $get        = $request->query->all();
        $post       = $request->request->all();
        $cookies    = $request->cookies->all();
        $attributes = $request->attributes->all();
        $files      = $request->files->all();
        $server     = $request->server->all();
        $headers    = $request->headers->all();
        return $this->json(
            [
                'files'      => $files,
                'server'     => $server,
                'attributes' => $attributes,
                'headers'    => $headers,
                'cookies'    => $cookies,
                'get'        => $get,
                'post'       => $post,
            ]
        );
    }
}
