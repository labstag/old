<?php

namespace Labstag\Lib;

use Symfony\Component\HttpFoundation\JsonResponse;

abstract class ApiControllerLib extends ControllerLib
{
    protected function trashAction(): JsonResponse
    {
        $get        = $this->request->query->all();
        $post       = $this->request->request->all();
        $cookies    = $this->request->cookies->all();
        $attributes = $this->request->attributes->all();
        $files      = $this->request->files->all();
        $server     = $this->request->server->all();
        $headers    = $this->request->headers->all();

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

    protected function restoreAction(): JsonResponse
    {
        $get        = $this->request->query->all();
        $post       = $this->request->request->all();
        $cookies    = $this->request->cookies->all();
        $attributes = $this->request->attributes->all();
        $files      = $this->request->files->all();
        $server     = $this->request->server->all();
        $headers    = $this->request->headers->all();

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

    protected function emptyAction(): JsonResponse
    {
        $get        = $this->request->query->all();
        $post       = $this->request->request->all();
        $cookies    = $this->request->cookies->all();
        $attributes = $this->request->attributes->all();
        $files      = $this->request->files->all();
        $server     = $this->request->server->all();
        $headers    = $this->request->headers->all();

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

    protected function deleteAction(): JsonResponse
    {
        $get        = $this->request->query->all();
        $post       = $this->request->request->all();
        $cookies    = $this->request->cookies->all();
        $attributes = $this->request->attributes->all();
        $files      = $this->request->files->all();
        $server     = $this->request->server->all();
        $headers    = $this->request->headers->all();

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
