<?php

namespace App\Controller\Api;

use App\Lib\AbstractControllerLib;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class UserApi extends AbstractControllerLib
{
    /**
     * @Route("/api/user/check", name="api_checkuser")
     */
    public function check(Request $request): JsonResponse
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
