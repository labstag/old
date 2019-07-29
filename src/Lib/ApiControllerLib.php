<?php

namespace Labstag\Lib;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Serializer\Encoder\CsvEncoder;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Encoder\XmlEncoder;
use Symfony\Component\Serializer\Normalizer\GetSetMethodNormalizer;
use Symfony\Component\Serializer\Serializer;

abstract class ApiControllerLib extends ControllerLib
{

    /**
     * @var Serializer
     */
    private $serializer;

    protected function trashAction(ServiceEntityRepositoryLib $repository, string $format)
    {
        $dataInTrash = $repository->findDataInTrash();
        $this->setSerializer();
        $content = $this->serializer->serialize($dataInTrash, $format);

        return new Response($content);
    }

    protected function restoreAction(ServiceEntityRepositoryLib $repository, string $format)
    {
        unset($format, $repository);
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

    protected function emptyAction(ServiceEntityRepositoryLib $repository, string $format)
    {
        unset($format, $repository);
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

    protected function deleteAction(ServiceEntityRepositoryLib $repository, string $format)
    {
        unset($format, $repository);
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

    private function setSerializer()
    {
        $encoders    = [
            new XmlEncoder(),
            new JsonEncoder(),
            new CsvEncoder(),
        ];
        $normalizers = new GetSetMethodNormalizer();

        $callback = function ($innerObject, $outerObject, string $attributeName, string $format = null, array $context = []) {
            unset($outerObject, $attributeName, $format, $context);

            return $innerObject instanceof \DateTime ? $innerObject->format(\DateTime::ISO8601) : '';
        };

        $normalizers->setCallbacks(
            [
                'createdAt' => $callback,
                'updatedAt' => $callback,
                'deletedAt' => $callback,
            ]
        );

        $this->serializer = new Serializer([$normalizers], $encoders);
    }
}
