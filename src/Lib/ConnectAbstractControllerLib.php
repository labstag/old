<?php

namespace Labstag\Lib;

use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

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
            'github' => [
                'params' => [
                    'urlAuthorize'            => 'https://github.com/login/oauth/authorize',
                    'urlAccessToken'          => 'https://github.com/login/oauth/access_token',
                    'urlResourceOwnerDetails' => 'https://api.github.com/user',
                ],
            ],
            'discord' => [
                'params' => [
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
            'google' => [
                'params' => [
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

    protected function getConfigProvider($code)
    {
        return (isset($this->configProvider[$code])) ? $this->configProvider[$code] : [];
    }

    protected function initProvider($clientName)
    {
        $config = $this->getConfigProvider($clientName);
        if (isset($config['redirect'])) {
            $config['params']['redirectUri'] = $this->generateUrl('connect_check', ['oauthCode' => $clientName], UrlGeneratorInterface::ABSOLUTE_URL);
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

    protected function connectRedirect(string $clientName)
    {
        $provider = $this->setProvider($clientName);

        if (is_null($provider)) {
            return;
        }

        $authUrl = $provider->getAuthorizationUrl();

        return $this->redirect(
            $authUrl
        );
    }

    protected function connectCheck(string $clientName)
    {
        $provider = $this->setProvider($clientName);

        if (is_null($provider)) {
            return;
        }

        $token = $provider->getAccessToken(
            'authorization_code',
            [
                'code' => $_GET['code'],
            ]
        );

        try {
            $user = $provider->getResourceOwner($token);
            dump($user);
            exit();
        } catch (Exception $e) {
            dump('Oh dear...');
            exit();
        }
    }
}
