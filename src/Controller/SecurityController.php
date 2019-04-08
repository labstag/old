<?php

namespace Labstag\Controller;

use Labstag\Form\Security\LoginType;
use Labstag\Lib\AbstractControllerLib;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class SecurityController extends AbstractControllerLib
{
    /**
     * @Route("/login", name="app_login")
     */
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        $lastUsername = $authenticationUtils->getLastUsername();
        $form         = $this->createForm(
            LoginType::class,
            ['username' => $lastUsername]
        );
        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
        // last username entered by the user

        return $this->twig(
            'security/login.html.twig',
            [
                'formLogin' => $form->createView(),
                'error'     => $error,
            ]
        );
    }
}
