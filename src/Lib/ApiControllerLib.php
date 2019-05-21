<?php

namespace Labstag\Lib;

use Labstag\Lib\ControllerLib;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;

abstract class ApiControllerLib extends ControllerLib
{

    protected function trashAction(Request $request): JsonResponse
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