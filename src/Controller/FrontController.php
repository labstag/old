<?php

namespace Labstag\Controller;

use Labstag\Lib\ControllerLib;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Routing\Annotation\Route;
use Labstag\Form\Front\DisclaimerType;

class FrontController extends ControllerLib
{
    /**
     * @Route("/", name="front")
     */
    public function index(): RedirectResponse
    {
        return $this->redirect(
            $this->generateUrl('posts_list'),
            301
        );
    }

    /**
     * @Route("/disclaimer", name="disclaimer")
     */
    public function disclaimer()
    {
        $form = $this->createForm(DisclaimerType::class, []);
        $form->handleRequest($this->request);
        $session = $this->request->getSession();
        if ($form->isSubmitted()) {
            $post = $this->request->request->get($form->getName());
            if (isset($post['confirm'])) {
                $session->set('disclaimer', 1);
                return $this->redirect(
                    $this->generateUrl('front')
                );
            }
        }

        $this->setConfigurationParam();
        return $this->twig(
            'disclaimer.html.twig',
            [
                'form'        => $form->createView(),
                'title'       => $this->paramViews['config']['disclaimer'][0]['title'],
                'message'     => $this->paramViews['config']['disclaimer'][0]['message'],
                'urlredirect' => $this->paramViews['config']['disclaimer'][0]['url-redirect'],
                'disclaimer'  => 0
            ]
        );
    }
}
