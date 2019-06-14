<?php

namespace Labstag\Lib;

use Gedmo\Loggable\Entity\LogEntry;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
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
    public function twig(string $view, array $parameters = [], Response $response = null): Response
    {
        $this->addParamViewsAdmin($parameters);
        $this->paramViews['disclaimer'] = 0;

        return parent::twig($view, $this->paramViews, $response);
    }

    protected function crudEnableAction(ServiceEntityRepositoryLib $repository, string $function): JsonResponse
    {
        $data          = json_decode($this->request->getContent(), true);
        $entityManager = $this->getDoctrine()->getManager();
        $entity        = $repository->find($data['id']);
        if (!$entity) {
            return $this->json([]);
        }

        $methods = get_class_methods($entity);
        if (!in_array($function, $methods)) {
            return $this->json([]);
        }

        $entity->{$function}(('true' == $data['state']));
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
        $tabDataCheck = [
            'datatable',
            'api',
            'title',
            'url_list',
            'url_trash',
            'repository',
        ];
        foreach ($tabDataCheck as $key) {
            if (!isset($data[$key])) {
                throw new HttpException(500, 'Parametre ['.$key.'] manquant');
            }
        }

        $this->setDatatable($data);
        $paramtwig = [
            'datatable' => $data['datatable'],
            'title'     => $data['title'],
            'operation' => true,
            'select'    => true,
            'api'       => $data['api'],
        ];

        $this->setParamTwig($paramtwig, $data);
        /**
         * @var ServiceEntityRepositoryLib
         */
        $repository  = $data['repository'];
        $dataInTrash = $repository->findDataInTrash();
        $route       = $this->request->attributes->get('_route');
        if (count($dataInTrash)) {
            $this->dateInTrash($paramtwig, $dataInTrash, $data);
        }

        if (0 == count($dataInTrash) && $route == $data['url_trash']) {
            $this->addFlash('info', 'Aucune donnée dans la corbeille');

            return $this->redirect(
                $this->generateUrl($data['url_list']),
                301
            );
        }

        $this->setOperationLink($paramtwig);

        return $this->twig('admin/crud/list.html.twig', $paramtwig);
    }

    protected function crudNewAction(array $data = [])
    {
        $tabDataCheck = [
            'form',
            'entity',
            'url_list',
            'url_edit',
            'title',
        ];
        foreach ($tabDataCheck as $key) {
            if (!isset($data[$key])) {
                throw new HttpException(500, 'Parametre ['.$key.'] manquant');
            }
        }

        $route = $this->request->attributes->get('_route');
        $form  = $this->createForm(
            $data['form'],
            $data['entity'],
            [
                'method' => 'POST',
                'action' => $this->generateUrl($route),
            ]
        );
        $form->handleRequest($this->request);

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
            'url_list' => $data['url_list'],
            'btnSave'  => true,
            'form'     => $form->createView(),
        ];

        if (isset($data['twig'])) {
            $params['twig'] = $data['twig'];
        }

        return $this->crudShowForm($params);
    }

    protected function crudEditAction(array $data = [])
    {
        $tabDataCheck  = [
            'form',
            'entity',
            'url_list',
            'url_edit',
            'url_delete',
            'title',
        ];
        $entityManager = $this->getDoctrine()->getManager();
        $repository    = $entityManager->getRepository(LogEntry::class);
        foreach ($tabDataCheck as $key) {
            if (!isset($data[$key])) {
                throw new HttpException(500, 'Parametre ['.$key.'] manquant');
            }
        }

        $route       = $this->request->attributes->get('_route');
        $routeParams = $this->request->attributes->get('_route_params');
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
        $form->handleRequest($this->request);
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
            'url_list'   => $data['url_list'],
            'btnSave'    => true,
            'url_delete' => $data['url_delete'],
            'form'       => $form->createView(),
        ];

        if (isset($data['twig'])) {
            $params['twig'] = $data['twig'];
        }

        if (!is_array($data['entity'])) {
            $params['logs'] = $repository->getLogEntries($data['entity']);
            dump($params['logs']);
        }

        if (isset($data['url_view'])) {
            $params['url_view'] = $data['url_view'];
        }

        return $this->crudShowForm($params);
    }

    protected function crudEmptyAction(ServiceEntityRepositoryLib $repository, string $route): JsonResponse
    {
        $data          = $repository->findDataInTrash();
        $entityManager = $this->getDoctrine()->getManager();
        foreach ($data as $entity) {
            $entityManager->remove($entity);
        }

        $entityManager->flush();
        $json = [
            'redirect' => $this->generateUrl($route, [], UrlGeneratorInterface::ABSOLUTE_PATH),
        ];

        return $this->json($json);
    }

    protected function crudRestoreAction(ServiceEntityRepositoryLib $repository, array $route): JsonResponse
    {
        $tabDataCheck = [
            'url_list',
            'url_trash',
        ];
        foreach ($tabDataCheck as $key) {
            if (!isset($route[$key])) {
                throw new HttpException(500, 'Parametre ['.$key.'] manquant');
            }
        }

        $data          = json_decode($this->request->getContent(), true);
        $entityManager = $this->getDoctrine()->getManager();
        $restore       = 0;
        $routeRedirect = $route['url_trash'];
        foreach ($data as $id) {
            $entity = $repository->findOneDateInTrash($id);
            if ($entity) {
                $entityManager->setDeletedAt(null);
                $entityManager->persist($entity);
                $restore = 1;
            }
        }

        if (1 == $restore) {
            $this->addFlash('success', 'Données restoré');
        }

        $entityManager->flush();
        $dataInTrash = $repository->findDataInTrash();
        if (count($dataInTrash)) {
            $routeRedirect = $route['url_list'];
        }

        $json = [
            'redirect' => $this->generateUrl(
                $routeRedirect,
                [],
                UrlGeneratorInterface::ABSOLUTE_PATH
            ),
        ];

        return $this->json($json);
    }

    protected function crudDeleteAction(ServiceEntityRepositoryLib $repository, array $route): JsonResponse
    {
        $tabDataCheck = [
            'url_list',
            'url_trash',
        ];
        foreach ($tabDataCheck as $key) {
            if (!isset($route[$key])) {
                throw new HttpException(500, 'Parametre ['.$key.'] manquant');
            }
        }

        $routeActual   = $this->request->attributes->get('_route');
        $data          = json_decode($this->request->getContent(), true);
        $entityManager = $this->getDoctrine()->getManager();
        $trash         = 0;
        $routeRedirect = $route['url_list'];
        if (0 != substr_count($routeActual, 'trash')) {
            $trash         = 1;
            $routeRedirect = $route['url_trash'];
        }

        $delete = 0;
        foreach ($data as $id) {
            $entity = $this->findEntity($trash, $repository, $id);
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
            'redirect' => $this->generateUrl($routeRedirect, [], UrlGeneratorInterface::ABSOLUTE_PATH),
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

    private function setOperationLink(&$paramtwig)
    {
        $tabDataCheck                = [
            'url_delete',
            'url_edit',
            'url_view',
            'url_custom',
            'url_duplicate',
        ];
        $paramtwig['operation_link'] = [];
        foreach ($tabDataCheck as $key) {
            if (isset($paramtwig[$key])) {
                $paramtwig['operation_link'][$key] = $paramtwig[$key];
            }
        }

        if (isset($paramtwig['url_empty'])) {
            unset($paramtwig['url_delete']);
        }
    }

    private function setParamTwig(&$paramtwig, $data)
    {
        $tabDataCheck = [
            'url_new',
            'url_view',
            'url_delete',
            'url_edit',
            'url_enable',
            'url_custom',
        ];
        foreach ($tabDataCheck as $key) {
            if (isset($data[$key])) {
                $paramtwig[$key] = $data[$key];
            }
        }
    }

    private function setDatatable(&$data)
    {
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
    }

    private function dateInTrash(&$paramtwig, $dataInTrash, $data)
    {
        $route                  = $this->request->attributes->get('_route');
        $paramtwig['url_trash'] = $data['url_trash'];
        $paramtwig['url_list']  = $data['url_list'];
        if ($route == $data['url_trash']) {
            unset($paramtwig['url_new'], $paramtwig['url_trash']);
            $paramtwig['dataInTrash'] = $dataInTrash;
            $paramtwig['url_empty']   = $data['url_empty'];
            $paramtwig['url_delete']  = $data['url_deletetrash'];
            $paramtwig['url_edit']    = $data['url_trashedit'];
            unset($paramtwig['api']);
            if (isset($data['url_trashview'])) {
                $paramtwig['url_view'] = $data['url_trashview'];
            }
        }
    }

    private function findEntity($trash, $repository, $dataInTrash)
    {
        if (0 == $trash) {
            return $repository->find($dataInTrash);
        }

        return $repository->findOneDateInTrash($dataInTrash);
    }

    private function setMenuAdmin()
    {
        $menuadmin = [
            [
                'url'   => 'admin_dashboard',
                'title' => 'Dashboard',
            ],
            [
                'url'   => 'adminconfiguration_list',
                'title' => 'Configuration',
            ],
            [
                'url'   => 'adminformbuilder_list',
                'title' => 'Form Builder',
            ],
            [
                'url'   => 'adminfullcalendar_list',
                'title' => 'Full Calendar',
            ],
            [
                'url'   => 'adminparam_list',
                'title' => 'Param',
            ],
            [
                'url'   => 'adminuser_list',
                'title' => 'User',
            ],
            [
                'url'   => 'adminworkflow_list',
                'title' => 'Workflow',
            ],
            [
                'title' => 'Articles',
                'child' => [
                    [
                        'url'   => 'adminpost_list',
                        'title' => 'Post',
                    ],
                    [
                        'url'   => 'adminposttags_list',
                        'title' => 'Tags',
                    ],
                    [
                        'url'   => 'adminpostcategory_list',
                        'title' => 'Catégory',
                    ],
                ],
            ],
            [
                'title' => 'Histoires',
                'child' => [
                    [
                        'url'   => 'adminhistory_list',
                        'title' => 'History',
                    ],
                    [
                        'url'   => 'adminhistorychapitre_list',
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
        unset($route1, $route2);

        if ($controller1 == $controller2) {
            return true;
        }
    }
}
