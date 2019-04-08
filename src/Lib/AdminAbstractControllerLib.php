<?php

namespace Labstag\Lib;

use Labstag\Lib\AbstractControllerLib;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\DependencyInjection\ContainerInterface;

abstract class AdminAbstractControllerLib extends AbstractControllerLib
{

    protected function actionDelete(Request $request, $entity, $route): Response
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

    protected function redirectForm($data): RedirectResponse
    {
        return $this->redirectToRoute(
            $data['url'],
            [
                'id' => $data['entity']->getId(),
            ]
        );
    }

    protected function showForm($data): Response
    {
        return $this->twig(
            'admin/crud/form.html.twig',
            $data
        );
    }
}
