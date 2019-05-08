<?php

namespace Labstag\Lib;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
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

    protected function crudEnableAction(Request $request, ServiceEntityRepositoryLib $repository, string $function): JsonResponse
    {
        $data          = json_decode($request->getContent(), true);
        $entityManager = $this->getDoctrine()->getManager();
        $entity        = $repository->find($data['id']);
        if (!$entity) {
            return $this->json([]);
        }

        $methods = get_class_methods($entity);
        if (!in_array($function, $methods)) {
            return $this->json([]);
        }

        $entity->{$function}($data['state']);
        $entityManager->persist($entity);
        $entityManager->flush();

        return $this->json([]);
    }

    /**
     * Generate crud list.
     *
     * @param array $data Données pour bootstrap-table
     */
    protected function crudListAction($data): Response
    {
        if (!isset($data['datatable'])) {
            throw new HttpException(500, 'Parametre [datatable] manquant');
        }

        if (!isset($data['api'])) {
            throw new HttpException(500, 'Parametre [api] manquant');
        }

        if (!isset($data['title'])) {
            throw new HttpException(500, 'Parametre [title] manquant');
        }

        if (!isset($data['total'])) {
            throw new HttpException(500, 'Parametre [total] manquant');
        }

        foreach ($data['datatable'] as &$row) {
            if (in_array($row['field'], ['updatedAt', 'createdAt'])) {
                $row['align'] = 'right';
            }

            if (isset($row['formatter']) && in_array($row['formatter'], ['dataTotalFormatter'])) {
                $row['align'] = 'right';
            }

            if (isset($row['formatter']) && in_array($row['formatter'], ['enableFormatter', 'imageFormatter'])) {
                $row['align'] = 'center';
            }

            if (!isset($row['valign'])) {
                $row['valign'] = 'top';
            }

            $row['sortable'] = true;
        }

        $paramtwig = [
            'datatable' => $data['datatable'],
            'title'     => $data['title'].' ('.$data['total'].')',
            'operation' => true,
            'select'    => true,
            'api'       => $data['api'],
        ];
        if (isset($data['url_new'])) {
            $paramtwig['url_new'] = $data['url_new'];
        }

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
            $this->addFlash('success', 'Données sauvegardé');

            return $this->crudRedirectForm(
                [
                    'url'    => $data['url_edit'],
                    'entity' => $data['entity'],
                ]
            );
        }

        $params = [
            'entity'   => $data['entity'],
            'title'    => $data['title'],
            'url_list' => $data['url_index'],
            'btnSave'  => true,
            'form'     => $form->createView(),
        ];

        if (isset($data['twig'])) {
            $params['twig'] = $data['twig'];
        }

        return $this->crudShowForm($params);
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
            $this->addFlash('success', 'Données sauvegardé');

            return $this->redirectToRoute(
                $data['url_edit'],
                [
                    'id' => $data['entity']->getId(),
                ]
            );
        }

        $params = [
            'entity'     => $data['entity'],
            'title'      => $data['title'],
            'url_list'   => $data['url_index'],
            'btnSave'    => true,
            'url_delete' => $data['url_delete'],
            'form'       => $form->createView(),
        ];

        if (isset($data['twig'])) {
            $params['twig'] = $data['twig'];
        }

        return $this->crudShowForm($params);
    }

    protected function crudDeleteAction(Request $request, ServiceEntityRepositoryLib $repository, string $route): JsonResponse
    {
        $data          = json_decode($request->getContent(), true);
        $entityManager = $this->getDoctrine()->getManager();
        $delete        = 0;
        foreach ($data as $id) {
            $entity = $repository->find($id);
            if ($entity) {
                $entityManager->remove($entity);
                $delete = 1;
            }
        }

        if (1 == $delete) {
            $this->addFlash('success', 'Données supprimé');
        }

        $entityManager->flush();

        $json = [
            'redirect' => $this->generateUrl($route, [], UrlGeneratorInterface::ABSOLUTE_PATH),
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
        $twig = 'admin/crud/form.html.twig';
        if (isset($data['twig'])) {
            $twig = $data['twig'];
            unset($data['twig']);
        }

        return $this->twig($twig, $data);
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
                'url'   => 'adminuser_index',
                'title' => 'User',
            ],
            [
                'url'   => 'adminworkflow_index',
                'title' => 'Workflow',
            ],
            [
                'title' => 'Articles',
                'child' => [
                    [
                        'url'   => 'adminpost_index',
                        'title' => 'Post',
                    ],
                    [
                        'url'   => 'adminposttags_index',
                        'title' => 'Tags',
                    ],
                    [
                        'url'   => 'adminpostcategory_index',
                        'title' => 'Catégory',
                    ],
                ],
            ],
            [
                'title' => 'Histoires',
                'child' => [
                    [
                        'url'   => 'adminhistory_index',
                        'title' => 'History',
                    ],
                    [
                        'url'   => 'adminhistorychapitre_index',
                        'title' => 'Chapitres',
                    ],
                ],
            ],
        ];

        $actualroute = $this->request->get('_route');
        $this->setMenuActualRoute($menuadmin, $actualroute);
        $this->paramViews['actualroute'] = $actualroute;
        $this->paramViews['menuadmin']   = $menuadmin;
    }

    private function setMenuActualRoute(&$menuadmin, $actualroute)
    {
        foreach ($menuadmin as &$menu) {
            if (isset($menu['child'])) {
                $this->setMenuActualRoute($menu['child'], $actualroute);
            } elseif ($this->isActualRoute($menu['url'], $actualroute)) {
                $menu['current'] = true;
            }
        }
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
