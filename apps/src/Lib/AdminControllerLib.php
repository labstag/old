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
        $this->paramViews['class_body'] = 'AdminPages';

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
            'datatable'     => $data['datatable'],
            'title'         => $data['title'],
            'operation'     => true,
            'select'        => true,
            'api'           => $data['api'],
            'api_param'     => [],
            'graphql_query' => [
                'table'  => '',
                'node'   => '',
                'params' => '',
            ],
        ];
        if (isset($data['api_param'])) {
            $paramtwig['api_param'] = $data['api_param'];
        }

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

        if ($route != $data['url_trash'] && isset($paramtwig['url_restore'])) {
            unset($paramtwig['url_restore']);
        }

        if (0 == count($dataInTrash) && $route == $data['url_trash']) {
            $this->addFlash('info', 'Aucune donnée dans la corbeille');

            return $this->redirect(
                $this->generateUrl($data['url_list']),
                301
            );
        }

        $this->setOperationLink($paramtwig);
        $this->setParamDatatable($paramtwig);

        return $this->twig('admin/crud/list.html.twig', $paramtwig);
    }

    /**
     * @throws HttpException
     *
     * @return RedirectResponse|Response
     */
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
            'logs'     => [],
        ];

        if (isset($data['twig'])) {
            $params['twig'] = $data['twig'];
        }

        return $this->crudShowForm($params);
    }

    protected function crudEditAction(array $data = []): response
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

        $this->generateLogs($params, $data, $entityManager);
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
            'redirect' => $this->generateUrl($route, [], UrlGeneratorInterface::ABSOLUTE_URL),
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

        $data = json_decode($this->request->getContent(), true);
        /** @var mixed $entityManager */
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
                UrlGeneratorInterface::ABSOLUTE_URL
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
            'redirect' => $this->generateUrl(
                $routeRedirect,
                [],
                UrlGeneratorInterface::ABSOLUTE_URL
            ),
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

    private function setAttrThOperation(array &$paramtwig): void
    {
        $paramtwig['attrthoperation'] = [];
        $data                         = [
            'formatter'  => 'operationDatatable',
            'align'      => 'center',
            'sortable'   => 'true',
            'valign'     => 'top',
            'switchable' => 'false',
            'searchable' => 'false',
        ];
        foreach ($data as $key => $value) {
            $paramtwig['attrthoperation']['data-'.$key] = $value;
        }

        ksort($paramtwig['attrthoperation']);
    }

    private function setAttrhField(array &$paramtwig): void
    {
        $paramtwig['attrthfields'] = [];
        if (!isset($paramtwig['datatable'])) {
            return;
        }

        foreach ($paramtwig['datatable'] as $name => $row) {
            $data = ['data-sortable' => 'true'];
            foreach ($row as $key => $value) {
                if (true === $value) {
                    $value = 'true';
                } elseif (false === $value) {
                    $value = 'false';
                }

                $data['data-'.$key] = $value;
            }

            ksort($data);
            $paramtwig['attrthfields'][$name] = $data;
        }
    }

    private function setDataGraphqlQuery(array $paramtwig, array &$data): void
    {
        if (!(isset($paramtwig['graphql_query']) && '' != $paramtwig['graphql_query']['table'])) {
            return;
        }

        foreach ($paramtwig['graphql_query'] as $key => $value) {
            $data['graphql-'.$key] = $value;
        }
    }

    private function setDataUrlEnable(array $paramtwig, array &$data): void
    {
        if (!isset($paramtwig['url_enable'])) {
            return;
        }

        foreach ($paramtwig['url_enable'] as $key => $value) {
            $data['enableurl-'.$key] = $value;
        }
    }

    private function setParamDatatable(array &$paramtwig): void
    {
        $this->setConfigurationParam();
        $attr = [
            'class' => 'table table-border table-hover table-striped table-sm thead-light',
            'id'    => 'CrudList',
        ];
        $data = [
            'toolbar'                 => '#toolbar',
            'resizable'               => 'true',
            'query-params'            => 'queryParams',
            'page-size'               => '10',
            'ajax-options'            => 'ajaxOptions',
            'moment'                  => $this->paramViews['config']['moment'][0]['format'],
            'files'                   => $this->generateUrl('front', [], UrlGeneratorInterface::ABSOLUTE_URL).'/file/',
            'toggle'                  => 'table',
            'locale'                  => $this->paramViews['config']['datatable'][0]['lang'],
            'total-field'             => 'count',
            'show-refresh'            => 'true',
            'show-columns'            => 'true',
            'show-fullscreen'         => 'true',
            'sortable'                => 'true',
            'sort-class'              => 'table-active',
            'side-pagination'         => 'server',
            'sort-stable'             => 'true',
            'search'                  => 'true',
            'advanced-search'         => 'true',
            'id-table'                => 'advancedTable',
            'show-jump-to'            => 'true',
            'mobile-responsive'       => 'true',
            'page-list'               => $this->paramViews['config']['datatable'][0]['pagelist'],
            'show-toggle'             => 'true',
            'id-field'                => 'id',
            'pagination'              => 'true',
            'show-export'             => 'true',
            'show-columns-toggle-all' => 'true',
            'cookie'                  => 'true',
            'cookie-id-table'         => 'saveId',
        ];
        $this->setDataUrlEnable($paramtwig, $data);
        $this->setDataGraphqlQuery($paramtwig, $data);
        if (isset($paramtwig['api'], $paramtwig['api_param'])) {
            $data['url'] = $this->generateUrl($paramtwig['api'], $paramtwig['api_param']);
        }

        foreach ($data as $key => $value) {
            $attr['data-'.$key] = $value;
        }

        ksort($attr);

        $paramtwig['attrdatatable'] = $attr;
        $this->setAttrThOperation($paramtwig);
        $this->setAttrhField($paramtwig);
    }

    /**
     * @param mixed $entityManager
     */
    private function generateLogs(array &$params, array $data, $entityManager): void
    {
        $repository = $entityManager->getRepository(LogEntry::class);
        if (is_array($data['entity'])) {
            return;
        }

        $logs     = $repository->getLogEntries($data['entity']);
        $dataLogs = [];
        foreach ($logs as $log) {
            if (!empty($log->username)) {
                array_push($dataLogs, $log);
            }
        }

        $params['logs'] = $dataLogs;
    }

    private function setOperationLink(array &$paramtwig): void
    {
        $tabDataCheck                = [
            'url_delete',
            'url_edit',
            'url_view',
            'url_custom',
            'url_restore',
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

    private function setParamTwig(array &$paramtwig, array $data): void
    {
        $tabDataCheck = [
            'url_restore',
            'url_new',
            'url_view',
            'url_delete',
            'url_edit',
            'url_enable',
            'url_custom',
            'graphql_query',
        ];
        foreach ($tabDataCheck as $key) {
            if (isset($data[$key])) {
                $paramtwig[$key] = $data[$key];
            }
        }
    }

    private function setDatatable(array &$data): void
    {
        /** @var array $row */
        foreach ($data['datatable'] as $id => $row) {
            $newrow = $row;
            if (in_array($row['field'], ['updatedAt', 'createdAt'])) {
                $newrow['align'] = 'right';
            }

            if (isset($row['formatter']) && in_array($row['formatter'], ['dataTotalFormatter'])) {
                $newrow['align'] = 'right';
            }

            if (isset($row['formatter']) && in_array($row['formatter'], ['enableFormatter', 'imageFormatter'])) {
                $newrow['align'] = 'center';
            }

            if (!isset($row['valign'])) {
                $newrow['valign'] = 'top';
            }

            $newrow['sortable']     = true;
            $data['datatable'][$id] = $newrow;
        }
    }

    /**
     * @param mixed $dataInTrash
     */
    private function dateInTrash(array &$paramtwig, $dataInTrash, array $data): void
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

    /**
     * @return mixed|null
     */
    private function findEntity(int $trash, ServiceEntityRepositoryLib $repository, string $dataInTrash)
    {
        if (0 == $trash) {
            return $repository->find($dataInTrash);
        }

        return $repository->findOneDateInTrash($dataInTrash);
    }

    private function setMenuAdmin(): void
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
                'url'   => 'admintemplate_list',
                'title' => 'Templates',
            ],
            [
                'title' => 'Bookmark',
                'child' => [
                    [
                        'url'   => 'adminbookmark_list',
                        'title' => 'Bookmark',
                    ],
                    [
                        'url'   => 'adminbookmarktags_list',
                        'title' => 'Tags',
                    ],
                ],
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

    private function setMenuActualRoute(array &$menuadmin, string $actualroute): void
    {
        /** @var array $menu */
        foreach ($menuadmin as $id => $menu) {
            $newmenu = $menu;
            if (isset($menu['child'])) {
                $this->setMenuActualRoute($newmenu['child'], $actualroute);
            } elseif ($this->isActualRoute($menu['url'], $actualroute)) {
                $newmenu['current'] = true;
            }

            $menuadmin[$id] = $newmenu;
        }
    }

    private function isActualRoute(string $url, string $actualroute): bool
    {
        if ($url == $actualroute) {
            return true;
        }

        $explode     = explode('_', $url);
        $controller1 = $explode[0];
        $explode     = explode('_', $actualroute);
        $controller2 = $explode[0];

        return 0 === strcmp((string) $controller1, (string) $controller2);
    }
}
