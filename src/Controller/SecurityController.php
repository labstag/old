<?php

namespace Labstag\Controller;

use Labstag\Form\Front\DisclaimerType;
use Labstag\Form\Security\LoginType;
use Labstag\Lib\ControllerLib;
use Labstag\Repository\OauthConnectUserRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Authentication\Token\AnonymousToken;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class SecurityController extends ControllerLib
{
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

            $this->addFlash('danger', "Veuillez accepter l'énoncé");
        }

        $this->setConfigurationParam();

        return $this->twig(
            'disclaimer.html.twig',
            [
                'form'        => $form->createView(),
                'title'       => $this->paramViews['config']['disclaimer'][0]['title'],
                'message'     => $this->paramViews['config']['disclaimer'][0]['message'],
                'urlredirect' => $this->paramViews['config']['disclaimer'][0]['url-redirect'],
                'disclaimer'  => 0,
            ]
        );
    }

    /**
     * @Route("/logout", name="app_logout")
     */
    public function logout(): Response
    {
    }

    /**
     * @Route("/login", name="app_login")
     */
    public function login(AuthenticationUtils $authenticationUtils, OauthConnectUserRepository $repository): Response
    {
        $token = $this->get('security.token_storage')->getToken();
        if (!($token instanceof AnonymousToken)) {
            return $this->redirect($this->generateUrl('front'), 302);
        }

        $lastUsername = $authenticationUtils->getLastUsername();
        $form         = $this->createForm(
            LoginType::class,
            ['username' => $lastUsername]
        );
        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
        // last username entered by the user

        $oauths = $repository->findDistinctAllOauth();

        return $this->twig(
            'security/login.html.twig',
            [
                'disclaimer' => 0,
                'oauths'     => $oauths,
                'formLogin'  => $form->createView(),
                'error'      => $error,
            ]
        );
    }
}
