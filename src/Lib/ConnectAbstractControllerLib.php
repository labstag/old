<?php

namespace Labstag\Lib;

use Labstag\Entity\User;
use Labstag\Entity\OauthConnectUser;
use Symfony\Component\HttpFoundation\Request;
use League\OAuth2\Client\Provider\GenericResourceOwner;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Core\Authentication\Token\AnonymousToken;

abstract class ConnectAbstractControllerLib extends AbstractControllerLib
{
    /**
     * @var array
     */
    protected $configProvider;

    public function __construct(ContainerInterface $container)
    {
        $this->setConfigProvider();
        parent::__construct($container);
    }

    protected function setConfigProvider()
    {
        $this->configProvider = [
            'bitbucket' => [
                'params' => [
                    'urlAuthorize'            => 'https://bitbucket.org/site/oauth2/authorize',
                    'urlAccessToken'          => 'https://bitbucket.org/site/oauth2/access_token',
                    'urlResourceOwnerDetails' => 'https://api.bitbucket.org/2.0/user',
                ],
            ],
            'github'    => [
                'params' => [
                    'urlAuthorize'            => 'https://github.com/login/oauth/authorize',
                    'urlAccessToken'          => 'https://github.com/login/oauth/access_token',
                    'urlResourceOwnerDetails' => 'https://api.github.com/user',
                ],
            ],
            'discord'   => [
                'params'         => [
                    'urlAuthorize'            => 'https://discordapp.com/api/v6/oauth2/authorize',
                    'urlAccessToken'          => 'https://discordapp.com/api/v6/oauth2/token',
                    'urlResourceOwnerDetails' => 'https://discordapp.com/api/v6/users/@me',
                ],
                'scopeseparator' => ' ',
                'scopes'         => [
                    'identify',
                    'email',
                    'connections',
                    'guilds',
                    'guilds.join',
                ],
            ],
            'google'    => [
                'params'         => [
                    'urlAuthorize'            => 'https://accounts.google.com/o/oauth2/v2/auth',
                    'urlAccessToken'          => 'https://www.googleapis.com/oauth2/v4/token',
                    'urlResourceOwnerDetails' => 'https://openidconnect.googleapis.com/v1/userinfo',
                ],
                'redirect'       => 1,
                'scopeseparator' => ' ',
                'scopes'         => [
                    'openid',
                    'email',
                    'profile',
                ],
            ],
        ];
    }

    protected function initProvider($clientName)
    {
        $config = (isset($this->configProvider[$clientName])) ? $this->configProvider[$clientName] : [];
        if (isset($config['redirect'])) {
            $config['params']['redirectUri'] = $this->generateUrl(
                'connect_check',
                [
                    'oauthCode' => $clientName,
                ],
                UrlGeneratorInterface::ABSOLUTE_URL
            );
        }

        $code                             = strtoupper($clientName);
        $config['params']['clientId']     = getenv('OAUTH_'.$code.'_ID');
        $config['params']['clientSecret'] = getenv('OAUTH_'.$code.'_SECRET');

        $provider = new GenericProviderLib(
            $config['params']
        );
        if (isset($config['scopes'])) {
            $provider->setDefaultScopes($config['scopes']);
        }

        if (isset($config['scopeseparator'])) {
            $provider->setScopeSeparator($config['scopeseparator']);
        }

        return $provider;
    }

    protected function setProvider($clientName)
    {
        if (isset($this->configProvider[$clientName])) {
            return $this->initProvider($clientName);
        }
    }

    protected function connectRedirect(Request $request, string $clientName)
    {
        $provider = $this->setProvider($clientName);

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

    protected function connectCheck(Request $request, string $clientName)
    {
        $provider    = $this->setProvider($clientName);
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

    private function addOauthToUser(string $client, User $user, GenericResourceOwner $userOauth)
    {
        $oauthConnects = $user->getOauthConnectUsers();
        $find = 0;
        foreach($oauthConnects as $oauthConnect)
        { 
            if ($oauthConnect->getName() == $client)
            {
                $find = 1;
                $this->addFlash("warning", "Compte ".$client." déjà associé à un autre utilisateur");
                break;
            }
        }

        if ($find === 0) {
            $oauthConnect = new OauthConnectUser();
            $oauthConnect->setRefuser($user);
            $oauthConnect->setName($client);
            $oauthConnect->setData($userOauth->toArray());
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($oauthConnect);
            $entityManager->flush();
            $this->addFlash("success", "Compte ".$client." associé à l'utilisateur ".$user);
        }
    }
}
