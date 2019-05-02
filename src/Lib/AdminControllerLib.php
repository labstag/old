<?php

namespace Labstag\Lib;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpKernel\Exception\HttpException;

abstract class AdminControllerLib extends ControllerLib
{
    /**
     * TODO: Le refaire pour prendre en compte bootstrap-table
     *
     * @param ServiceEntityRepositoryLib $repository
     * @return void
     */
    protected function crudListAction($data): Response
    {
        if (!isset($data['datatable'])) {
            throw new HttpException(500, 'Parametre [datatable] manquant');
        }

        if (!isset($data['api'])) {
            throw new HttpException(500, 'Parametre [api] manquant');
        }

        if (!isset($data['new'])) {
            throw new HttpException(500, 'Parametre [new] manquant');
        }

        if (!isset($data['title'])) {
            throw new HttpException(500, 'Parametre [title] manquant');
        }
        return $this->twig(
            'admin/crud/index.html.twig',
            [
                'datatable' => $data['datatable'],
                'title'     => $data['title'],
                'operation' => true,
                'select'    => true,
                'api'       => $data['api'],
                'new'       => $data['new'],
            ]
        );
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

    /**
     * Show.
     *
     * @param string    $view       template
     * @param array     $parameters data
     * @param ?Response $response   ??
     */
    public function twig(
        string $view,
        array $parameters = [],
        Response $response = null
    ): Response
    {
        $this->addParamViewsAdmin($parameters);
        return parent::twig($view, $parameters, $response);
    }

    /**
     * Add param to twig.
     */
    protected function addParamViewsAdmin(array $parameters = []): void
    {
        $this->setMenuAdmin();
        $this->paramViews = array_merge($parameters, $this->paramViews);
    }

    private function setMenuAdmin()
    {
        $menuadmin = [
            [
                'url' => 'admin_dashboard',
                'title' => 'Dashboard'
            ],
            [
                'url' => 'admincategory_index',
                'title' => 'Catégory'
            ],
            [
                'url' => 'adminconfiguration_index',
                'title' => 'Configuration'
            ],
            [
                'url' => 'adminformbuilder_index',
                'title' => 'Form Builder'
            ],
            [
                'url' => 'adminfullcalendar_index',
                'title' => 'Full Calendar'
            ],
            [
                'url' => 'adminparam_index',
                'title' => 'Param'
            ],
            [
                'url' => 'adminpost_index',
                'title' => 'Post'
            ],
            [
                'url' => 'admintags_index',
                'title' => 'Tags'
            ],
            [
                'url' => 'adminuser_index',
                'title' => 'User'
            ],
            [
                'url' => 'adminworkflow_index',
                'title' => 'Workflow'
            ]
        ];

        $actualroute = $this->request->get('_route');
        foreach ($menuadmin as &$menu) {
            if ($this->isActualRoute($menu['url'], $actualroute)) {
                $menu['current'] = true;
            }
        }

        $this->paramViews['actualroute'] = $actualroute;
        $this->paramViews['menuadmin']   = $menuadmin;
    }

    private function isActualRoute($url, $actualroute) {
        if ($url == $actualroute) {
            return true;
        }

        list($controller1, $route1) = explode("_", $url);
        list($controller2, $route2) = explode("_", $actualroute);
        if ($controller1 == $controller2) {
            return true;
        }
    }
}
