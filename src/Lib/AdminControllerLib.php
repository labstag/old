<?php

namespace Labstag\Lib;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

abstract class AdminControllerLib extends ControllerLib
{
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

        return parent::twig($view, $this->paramViews, $response);
    }

    protected function crudEnableAction(Request $request, ServiceEntityRepositoryLib $repository) :JsonResponse
    {
        $data          = json_decode($request->getContent(),true);
        $entityManager = $this->getDoctrine()->getManager();
        $entity = $repository->find($data['id']);
        $entity->setEnable($data['state']);
        $entityManager->persist($entity);
        $entityManager->flush();
        return $this->json([]);
    }

    /**
     * TODO: Le refaire pour prendre en compte bootstrap-table.
     *
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

        if (!isset($data['url_new'])) {
            throw new HttpException(500, 'Parametre [url_new] manquant');
        }

        if (!isset($data['title'])) {
            throw new HttpException(500, 'Parametre [title] manquant');
        }

        $paramtwig = [
            'datatable' => $data['datatable'],
            'title'     => $data['title'],
            'operation' => true,
            'select'    => true,
            'api'       => $data['api'],
            'url_new'   => $data['url_new'],
        ];
        if (isset($data['url_delete'])) {
            $paramtwig['url_delete'] = $data['url_delete'];
        }

        if (isset($data['url_edit'])) {
            $paramtwig['url_edit'] = $data['url_edit'];
        }

        if (isset($data['url_enable'])) {
            $paramtwig['url_enable'] = $data['url_enable'];
        }

        return $this->twig('admin/crud/index.html.twig', $paramtwig);
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

        $route = $request->attributes->get('_route');
        $form  = $this->createForm(
            $data['form'],
            $data['entity'],
            [
                'method' => 'POST',
                'action' => $this->generateUrl($route),
            ]
        );
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->persistAndFlush($data['entity']);
            $this->addFlash('success','Données sauvegardé');
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
                'url_list' => $data['url_index'],
                'btnSave'  => true,
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

        if (!isset($data['url_delete'])) {
            throw new HttpException(500, 'Parametre [url_delete] manquant');
        }

        if (!isset($data['title'])) {
            throw new HttpException(500, 'Parametre [title] manquant');
        }

        $route       = $request->attributes->get('_route');
        $routeParams = $request->attributes->get('_route_params');
        $form        = $this->createForm(
            $data['form'],
            $data['entity'],
            [
                'action' => $this->generateUrl($route, $routeParams),
                'method' => 'POST',
                'attr'   => [
                    'data-id' => $routeParams['id'],
                ],
            ]
        );
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();
            $this->addFlash('success','Données sauvegardé');
            return $this->redirectToRoute(
                $data['url_edit'],
                [
                    'id' => $data['entity']->getId(),
                ]
            );
        }

        return $this->crudShowForm(
            [
                'entity'     => $data['entity'],
                'title'      => $data['title'],
                'url_list'   => $data['url_index'],
                'btnSave'    => true,
                'url_delete' => $data['url_delete'],
                'form'       => $form->createView(),
            ]
        );
    }

    protected function crudDeleteAction(Request $request, ServiceEntityRepositoryLib $repository, string $route): JsonResponse
    {
        $data          = json_decode($request->getContent(), true);
        $entityManager = $this->getDoctrine()->getManager();
        foreach ($data as $id) {
            $entity = $repository->find($id);

            $entityManager->remove($entity);
        }

        $entityManager->flush();

        $json = [
            'redirect' => $this->generateUrl($route,[], UrlGeneratorInterface::ABSOLUTE_PATH)
        ];
        return $this->json($json);
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
                'url'   => 'admin_dashboard',
                'title' => 'Dashboard',
            ],
            [
                'url'   => 'admincategory_index',
                'title' => 'Catégory',
            ],
            [
                'url'   => 'adminconfiguration_index',
                'title' => 'Configuration',
            ],
            [
                'url'   => 'adminformbuilder_index',
                'title' => 'Form Builder',
            ],
            [
                'url'   => 'adminfullcalendar_index',
                'title' => 'Full Calendar',
            ],
            [
                'url'   => 'adminparam_index',
                'title' => 'Param',
            ],
            [
                'url'   => 'adminpost_index',
                'title' => 'Post',
            ],
            [
                'url'   => 'adminhistory_index',
                'title' => 'History',
            ],
            [
                'url'   => 'adminchapitre_index',
                'title' => 'Chapitres',
            ],
            [
                'url'   => 'admintags_index',
                'title' => 'Tags',
            ],
            [
                'url'   => 'adminuser_index',
                'title' => 'User',
            ],
            [
                'url'   => 'adminworkflow_index',
                'title' => 'Workflow',
            ],
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

    private function isActualRoute($url, $actualroute)
    {
        if ($url == $actualroute) {
            return true;
        }

        [
            $controller1,
            $route1,
        ] = explode('_', $url);
        [
            $controller2,
            $route2,
        ] = explode('_', $actualroute);
        if ($controller1 == $controller2) {
            return true;
        }
    }
}
