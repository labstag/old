<?php

namespace Labstag\Controller\Api;

use Labstag\Lib\ControllerLib;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class UserApi extends ControllerLib
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
