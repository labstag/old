<?php

namespace Labstag\Lib;

use Labstag\Lib\ServiceEntityRepositoryLib;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpKernel\Exception\HttpException;

abstract class AdminAbstractControllerLib extends AbstractControllerLib
{

    protected function crudListAction(ServiceEntityRepositoryLib $repository)
    {
        $entities = $repository->findAll();
        $this->paginator($entities);
    }

    protected function crudNewAction(Request $request, array $data = [])
    {
        if (!isset($data['form'])) {
            throw new HttpException(500, 'Parametre [FORM] manquant');
        }

        if (!isset($data['entity'])) {
            throw new HttpException(500, 'Parametre [entity] manquant');
        }

        if (!isset($data['url_index'])) {
            throw new HttpException(500, 'Parametre [url_index] manquant');
        }

        if (!isset($data['url_edit'])) {
            throw new HttpException(500, 'Parametre [url_edit] manquant');
        }

        if (!isset($data['title'])) {
            throw new HttpException(500, 'Parametre [title] manquant');
        }

        $form = $this->createForm($data['form'], $data['entity']);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->persistAndFlush($data['entity']);

            return $this->crudRedirectForm(
                [
                    'url'    => $data['url_edit'],
                    'entity' => $data['entity'],
                ]
            );
        }

        return $this->crudShowForm(
            [
                'entity'   => $data['entity'],
                'title'    => $data['title'],
                'url_back' => $data['url_index'],
                'form'     => $form->createView(),
            ]
        );
    }

    protected function crudEditAction(Request $request, array $data = [])
    {
        if (!isset($data['form'])) {
            throw new HttpException(500, 'Parametre [FORM] manquant');
        }

        if (!isset($data['entity'])) {
            throw new HttpException(500, 'Parametre [entity] manquant');
        }

        if (!isset($data['url_index'])) {
            throw new HttpException(500, 'Parametre [url_index] manquant');
        }

        if (!isset($data['url_edit'])) {
            throw new HttpException(500, 'Parametre [url_edit] manquant');
        }

        if (!isset($data['title'])) {
            throw new HttpException(500, 'Parametre [title] manquant');
        }

        $form = $this->createForm($data['form'], $data['entity']);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute(
                $data['url_edit'],
                [
                    'id' => $data['entity']->getId(),
                ]
            );
        }

        return $this->crudShowForm(
            [
                'entity'   => $data['entity'],
                'title'    => $data['title'],
                'url_back' => $data['url_index'],
                'form'     => $form->createView(),
            ]
        );
    }

    protected function crudActionDelete(Request $request, $entity, string $route): Response
    {
        $token = $request->request->get('_token');
        $uuid  = $entity->getId();
        if ($this->isCsrfTokenValid('delete'.$uuid, $token)) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($entity);
            $entityManager->flush();
        }

        return $this->redirectToRoute($route);
    }

    protected function persistAndFlush(&$entity): void
    {
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($entity);
        $entityManager->flush();
    }

    protected function crudRedirectForm(array $data = []): RedirectResponse
    {
        if (!isset($data['url'])) {
            throw new HttpException(500, 'Parametre [URL] manquant');
        }

        if (!isset($data['entity'])) {
            throw new HttpException(500, 'Parametre [ENTITY] manquant');
        }

        return $this->redirectToRoute(
            $data['url'],
            [
                'id' => $data['entity']->getId(),
            ]
        );
    }

    protected function crudShowForm(array $data = []): Response
    {
        return $this->twig(
            'admin/crud/form.html.twig',
            $data
        );
    }
}
