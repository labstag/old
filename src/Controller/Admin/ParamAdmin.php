<?php

namespace Labstag\Controller\Admin;

use Labstag\Entity\Configuration;
use Labstag\Form\Admin\ParamType;
use Labstag\Lib\AdminControllerLib;
use Labstag\Repository\ConfigurationRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin/param")
 */
class ParamAdmin extends AdminControllerLib
{
    /**
     * @Route("/", name="adminparam_list")
     */
    public function list(ConfigurationRepository $repository): Response
    {
        $this->setConfigurationParam();
        $config = $this->paramViews['config'];
        $form   = $this->createForm(ParamType::class, $config);
        $form->handleRequest($this->request);
        if ($form->isSubmitted()) {
            $post    = $this->request->request->get($form->getName());
            $manager = $this->getDoctrine()->getManager();
            foreach ($post as $key => $value) {
                if ('_token' == $key) {
                    continue;
                }

                $configuration = $repository->findOneBy(['name' => $key]);
                if (!$configuration) {
                    $configuration = new Configuration();
                    $configuration->setName($key);
                }

                $configuration->setValue($value);
                $manager->persist($configuration);
            }

            $manager->flush();
            $this->addFlash('success', 'Données sauvegardé');
        }

        return $this->twig(
            'admin/param.html.twig',
            [
                'title'   => 'Paramètres',
                'btnSave' => true,
                'form'    => $form->createView(),
            ]
        );
    }
}
