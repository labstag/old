<?php

namespace Labstag\Controller;

use Labstag\Entity\Templates;
use Labstag\Entity\User;
use Labstag\Form\Front\DisclaimerType;
use Labstag\Form\Security\LoginType;
use Labstag\Form\Security\LostPasswordType;
use Labstag\Lib\ControllerLib;
use Labstag\Repository\OauthConnectUserRepository;
use Swift_Message;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
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
                'class_body'  => 'DisclaimerPage',
                'form'        => $form->createView(),
                'title'       => $this->paramViews['config']['disclaimer'][0]['title'],
                'message'     => $this->paramViews['config']['disclaimer'][0]['message'],
                'urlredirect' => $this->paramViews['config']['disclaimer'][0]['url-redirect'],
                'disclaimer'  => 0,
            ]
        );
    }

    private function postLostPassword($post)
    {
        if (array_key_exists('username', $post)) {
            $field = 'username';
            $value = $post['username'];
        } elseif (array_key_exists('email', $post)) {
            $field = 'email';
            $value = $post['email'];
        }

        $manager = $this->getDoctrine()->getManager();

        $repository = $manager->getRepository(User::class);
        $user       = $repository->findOneBy(
            [
                $field => $value
            ]
        );
        if (!$user) {
            return;
        }

        $repository = $manager->getRepository(Templates::class);
        $search     = ['code' => 'lost-password'];
        $templates  = $repository->findOneBy($search);
        $html       = $templates->getHtml();
        $text       = $templates->getText();
        $this->setConfigurationParam();
        $before = [
            '%site%',
            '%username%',
            '%phone%',
            '%url%',
        ];
        $after   = [
            $this->paramViews['config']['site_title'],
            $user->getUsername(),
            $entity->getNumero(),
            $this->router->generate(
                'change-password',
                [
                    'id' => $entity->getId(),
                ],
                UrlGeneratorInterface::ABSOLUTE_URL
            ),
        ];
        $html    = str_replace($before, $after, $html);
        $text    = str_replace($before, $after, $text);
        // $message = new Swift_Message();
        // $sujet   = str_replace(
        //     '%site%',
        //     $this->paramViews['config']['site_title'],
        //     $templates->getname()
        // );
        // $message->setSubject($sujet);
        // $message->setFrom($user->getEmail());
        // $message->setTo($this->paramViews['config']['site_no-reply']);
        // $message->setBody($html, 'text/html');
        // $message->addPart($text, 'text/plain');
        // $mailer = $this->container->get('swiftmailer.mailer.default');
        // $mailer->send($message);
    }

    /**
     * @Route("/change-password/{id}", name="app_changepassword")
     */
    public function changePassword(User $user): Response
    {
        return $this->json([$user]);
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
                'class_body' => 'LoginPage',
                'disclaimer' => 0,
                'oauths'     => $oauths,
                'formLogin'  => $form->createView(),
                'error'      => $error,
            ]
        );
    }
}
