<?php

namespace Labstag\Lib;

use DateTimeInterface;
use Knp\Bundle\PaginatorBundle\Pagination\SlidingPagination;
use Knp\Component\Pager\Paginator;
use Knp\Component\Pager\PaginatorInterface;
use Labstag\Entity\Configuration;
use Labstag\Repository\ConfigurationRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Router;
use Symfony\Component\Routing\RouterInterface;

abstract class ControllerLib extends AbstractController
{

    /**
     * @var Request
     */
    protected $request;

    /**
     * Parameters for twig.
     *
     * @var array
     */
    protected $paramViews;

    /**
     * @var ContainerInterface
     */
    protected $container;

    /**
     * @var PaginatorInterface
     */
    private $paginator;

    /**
     * @var Router|RouterInterface
     */
    private $router;

    /**
     * @var RequestStack
     */
    private $requestStack;

    /**
     * Init controller.
     *
     * @param ContainerInterface $container container
     */
    public function __construct(
        ContainerInterface $container,
        PaginatorInterface $paginator,
        RequestStack $requestStack,
        RouterInterface $router
    )
    {
        $this->paramViews   = [];
        $this->container    = $container;
        $this->paginator    = $paginator;
        $this->requestStack = $requestStack;
        $this->router       = $router;
        /** @var Request $request */
        $request       = $this->requestStack->getCurrentRequest();
        $this->request = $request;
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
        $this->addParamViewsSite($parameters);
        if ($this->disclaimerActivate($parameters)) {
            return $this->redirect(
                $this->generateUrl('disclaimer')
            );
        }

        return parent::render($view, $this->paramViews, $response);
    }

    /**
     * Add param to twig.
     */
    protected function addParamViewsSite(array $parameters = []): void
    {
        $this->setConfigurationParam();
        $this->setMetaWithEntity($parameters);
        $this->paramViews = array_merge($parameters, $this->paramViews);
    }

    protected function paginator($query): void
    {
        /** @var SlidingPagination $pagination */
        $pagination = $this->paginator->paginate(
            $query,
            // query NOT result
            $this->request->query->getInt('page', 1),
            // page number
            10
            // limit per page
        );
        $pagination->setTemplate('paginator/pagination.html.twig');
        $pagination->setSortableTemplate('paginator/sortable.html.twig');
        $pagination->setFiltrationTemplate('paginator/filtration.html.twig');

        $this->paramViews['pagination'] = $pagination;
    }

    protected function setConfigurationParam(): void
    {
        if (isset($this->paramViews['config'])) {
            return;
        }

        $manager = $this->getDoctrine()->getManager();
        /** @var ConfigurationRepository $repository */
        $repository = $manager->getRepository(Configuration::class);
        $data       = $repository->findAll();
        $config     = [];
        /** @var configuration $row */
        foreach ($data as $row) {
            $key          = $row->getName();
            $value        = $row->getValue();
            $config[$key] = $value;
        }

        if (isset($config['oauth'])) {
            $oauth = [];
            foreach ($config['oauth'] as $data) {
                if (1 == $data['activate']) {
                    $type         = $data['type'];
                    $oauth[$type] = $data;
                }
            }

            $this->paramViews['oauth_activated'] = $oauth;
        }

        $this->paramViews['config'] = $config;
    }

    /**
     * @param mixed $entity
     */
    protected function persistAndFlush(&$entity): void
    {
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($entity);
        $entityManager->flush();
    }

    private function disclaimerActivate(array $parameters): bool
    {
        $session = $this->request->getSession();
        if (1 != $session->get('disclaimer') && !isset($parameters['disclaimer']) && true == $this->paramViews['config']['disclaimer'][0]['activate']) {
            $route = $this->request->attributes->get('_route');
            if ('disclaimer' != $route) {
                return true;
            }
        }

        return false;
    }

    private function setMetaWithEntity(array $parameters): void
    {
        if (isset($parameters['setMeta'])) {
            $entity = $parameters['setMeta'];

            $this->paramViews['config']['meta'][0] = [
                'article:published_time' => $entity->getCreatedAt()->format(
                    DateTimeInterface::ATOM
                ),
                'article:modified_time'  => $entity->getUpdatedAt()->format(
                    DateTimeInterface::ATOM
                ),
            ];
        }
    }
}
