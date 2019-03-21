<?php

namespace App\Controller;

use App\Form\Security\LoginType;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use App\Lib\AbstractControllerLib;

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
            [
                'username' => $lastUsername,
            ]
        );
        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
        // last username entered by the user

        return $this->twig(
            'security/login.html.twig',
            array(
                'formLogin' => $form->createView(),
                'error'     => $error,
            )
        );
    }
}
