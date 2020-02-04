<?php

namespace Labstag\Lib;

use Symfony\Component\HttpFoundation\JsonResponse;
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

    protected function trashAction(ServiceEntityRepositoryLib $repository): Response
    {
        $dataInTrash = $repository->findDataInTrash();

        return new Response($this->serializerData($dataInTrash));
    }

    protected function restoreAction(ServiceEntityRepositoryLib $repository): JsonResponse
    {
        unset($repository);

        return $this->setJson();
    }

    protected function emptyAction(ServiceEntityRepositoryLib $repository): JsonResponse
    {
        unset($repository);

        return $this->setJson();
    }

    protected function deleteAction(ServiceEntityRepositoryLib $repository): JsonResponse
    {
        unset($repository);

        return $this->setJson();
    }

    private function setJson(): JsonResponse
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

    /**
     * @param mixed $data
     */
    private function serializerData($data): string
    {
        $this->setSerializer();
        $accept = $this->request->server->get('HTTP_ACCEPT');
        switch ($accept) {
            case 'text/csv':
                $serialize = 'csv';

                break;
            case 'text/xml':
                $serialize = 'xml';

                break;
            default:
                $serialize = 'json';
        }

        return $this->serializer->serialize($data, $serialize);
    }

    private function setSerializer(): void
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
