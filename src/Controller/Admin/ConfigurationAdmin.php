<?php

namespace Labstag\Controller\Admin;

use Labstag\Entity\Configuration;
use Labstag\Form\Admin\ConfigurationType;
use Labstag\Lib\AdminAbstractControllerLib;
use Labstag\Repository\ConfigurationRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin/configuration")
 */
class ConfigurationAdmin extends AdminAbstractControllerLib
{
    /**
     * @Route("/", name="adminconfiguration_index", methods={"GET"})
     */
    public function index(
        ConfigurationRepository $configurationRepository
    ): Response {
        $configurations = $configurationRepository->findAll();
        $this->paginator($configurations);

        return $this->twig('admin/configuration/index.html.twig');
    }

    /**
     * @Route("/new", name="adminconfiguration_new", methods={"GET", "POST"})
     */
    public function new(Request $request): Response
    {
        $configuration = new Configuration();
        $form          = $this->createForm(
            ConfigurationType::class,
            $configuration
        );
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($configuration);
            $entityManager->flush();

            return $this->redirectToRoute('adminconfiguration_index');
        }

        return $this->showForm(
            [
                'entity'   => $configuration,
                'title'    => 'Add new configuration',
                'url_back' => 'adminconfiguration_index',
                'form'     => $form->createView(),
            ]
        );
    }

    /**
     * @Route("/{id}/edit", name="adminconfiguration_edit", methods={"GET", "POST"})
     */
    public function edit(
        Request $request,
        Configuration $configuration
    ): Response {
        $form = $this->createForm(ConfigurationType::class, $configuration);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectForm(
                [
                    'url'    => 'adminconfiguration_edit',
                    'entity' => $post,
                ]
            );
        }

        return $this->showForm(
            [
                'entity'   => $configuration,
                'title'    => 'Edit configuration',
                'url_back' => 'adminconfiguration_index',
                'form'     => $form->createView(),
            ]
        );
    }

    /**
     * @Route("/{id}", name="adminconfiguration_delete", methods={"DELETE"})
     */
    public function delete(
        Request $request,
        Configuration $configuration
    ): Response {
        return $this->actionDelete($request, $configuration, 'adminconfiguration_index');
    }
}
