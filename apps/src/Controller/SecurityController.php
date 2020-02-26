<?php

namespace Labstag\Controller;

use Labstag\Entity\User;
use Labstag\Form\Front\DisclaimerType;
use Labstag\Form\Security\ChangePasswordType;
use Labstag\Form\Security\LoginType;
use Labstag\Form\Security\LostPasswordType;
use Labstag\Lib\ControllerLib;
use Labstag\Repository\OauthConnectUserRepository;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Authentication\Token\AnonymousToken;
use Symfony\Component\Security\Core\Authentication\Token\Storage\UsageTrackingTokenStorage;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class SecurityController extends ControllerLib
{
    /**
     * @Route("/disclaimer", name="disclaimer")
     *
     * @return RedirectResponse|Response
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
                'class_body'  => 'DisclaimerPage',
                'form'        => $form->createView(),
                'title'       => $this->paramViews['config']['disclaimer'][0]['title'],
                'message'     => $this->paramViews['config']['disclaimer'][0]['message'],
                'urlredirect' => $this->paramViews['config']['disclaimer'][0]['url-redirect'],
                'disclaimer'  => 0,
            ]
        );
    }

    /**
     * @Route("/change-password/{id}", name="app_changepassword")
     */
    public function changePassword(User $user): Response
    {
        if ($user->isLost()) {
            $this->addFlash('danger', 'Demande de mot de passe non envoyé');

            return $this->redirect($this->generateUrl('front'), 302);
        }

        $user->setLost(false);

        $form = $this->createForm(ChangePasswordType::class, $user);
        $form->handleRequest($this->request);
        if ($form->isSubmitted()) {
            $post = $this->request->request->get($form->getName());
            unset($post);
        }

        return $this->twig(
            'security/change-password.html.twig',
            [
                'class_body'         => 'LoginPage',
                'formChangePassword' => $form->createView(),
                'disclaimer'         => 0,
            ]
        );
    }

    /**
     * @Route("/lost", name="app_lost")
     */
    public function lost(): Response
    {
        $form = $this->createForm(LostPasswordType::class);
        $form->handleRequest($this->request);
        if ($form->isSubmitted()) {
            $post = $this->request->request->get($form->getName());
            $this->postLostPassword($post);
        }

        return $this->twig(
            'security/lost-password.html.twig',
            [
                'class_body'       => 'LoginPage',
                'formLostPassword' => $form->createView(),
                'disclaimer'       => 0,
            ]
        );
    }

    /**
     * @Route("/logout", name="app_logout")
     */
    public function logout(): void
    {
    }

    /**
     * @Route("/login", name="app_login")
     */
    public function login(AuthenticationUtils $authenticationUtils, OauthConnectUserRepository $repository): Response
    {
        /** @var UsageTrackingTokenStorage $tokenStorage */
        $tokenStorage = $this->get('security.token_storage');
        $token        = $tokenStorage->getToken();
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
                'class_body' => 'LoginPage',
                'disclaimer' => 0,
                'oauths'     => $oauths,
                'formLogin'  => $form->createView(),
                'error'      => $error,
            ]
        );
    }

    private function postLostPassword(array $post): void
    {
        $field = '';
        $value = '';
        if (array_key_exists('username', $post)) {
            $field = 'username';
            $value = $post['username'];
        } elseif (array_key_exists('email', $post)) {
            $field = 'email';
            $value = $post['email'];
        }

        $manager    = $this->getDoctrine()->getManager();
        $repository = $manager->getRepository(User::class);
        if ('' === $value || '' === $field) {
            return;
        }

        /** @var User $user */
        $user = $repository->findOneBy(
            [$field => $value]
        );
        if (!($user instanceof User)) {
            return;
        }

        $user->setLost(true);
        $this->persistAndFlush($user);
        $this->addFlash('danger', 'Demande de nouveau mot de passe envoyé');
    }
}
