<?php

namespace Labstag\Controller;

use Labstag\Lib\AbstractControllerLib;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Labstag\Services\OauthServices;

class OauthController extends AbstractControllerLib
{
    /**
     * @var OauthServices
     */
    private $oauthServices;

    public function __construct(ContainerInterface $container)
    {
        parent::__construct($container);
        $this->oauthServices = $this->container->get(OauthServices::class);
    }
    /**
     * Link to this controller to start the "connect" process.
     *
     * @Route("/connect/{oauthCode}", name="connect_start")
     */
    public function connectAction(Request $request, string $oauthCode)
    {
        $provider = $this->oauthServices->setProvider($oauthCode);

        if (is_null($provider)) {
            $this->addFlash("warning", "Connexion Oauh impossible");
            return $this->redirect(
                $this->generateUrl('front')
            );
        }

        $authUrl = $provider->getAuthorizationUrl();
        $session = $request->getSession();
        $session->set('oauth2state', $provider->getState());
        return $this->redirect(
            $authUrl
        );
    }

    /**
     * After going to Github, you're redirected back here
     * because this is the "redirect_route" you configured
     * in config/packages/knpu_oauth2_client.yaml.
     *
     * @Route("/connect/{oauthCode}/check", name="connect_check")
     */
    public function connectCheckAction(Request $request, string $oauthCode)
    {
        $provider    = $this->oauthServices->setProvider($oauthCode);
        $query       = $request->query->all();
        $session     = $request->getSession();
        $oauth2state = $session->get('oauth2state');
        if (is_null($provider) || !isset($query['code']) || $oauth2state !== $query['state']) {
            $session->remove('oauth2state');
            $this->addFlash("warning", "Probleme d'identification");
            return $this->redirect(
                $this->generateUrl('front')
            );
        }

        try {
            $tokenProvider = $provider->getAccessToken(
                'authorization_code',
                [
                    'code' => $query['code'],
                ]
            );

            $session->remove('oauth2state');
            $userOauth = $provider->getResourceOwner($tokenProvider);
            $token     = $this->get('security.token_storage')->getToken();
            if (!($token instanceof AnonymousToken)) {
                $user = $token->getUser();
                $this->addOauthToUser($clientName, $user, $userOauth);
                dump($user);
            }

            return $this->redirect(
                $this->generateUrl('front')
            );
        } catch (Exception $e) {
            $this->addFlash("warning", "Probleme d'identification");
            return $this->redirect(
                $this->generateUrl('front')
            );
            exit();
        }
    }
}
