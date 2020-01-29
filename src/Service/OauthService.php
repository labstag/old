<?php

namespace Labstag\Service;

use Labstag\Lib\GenericProviderLib;
use Symfony\Bundle\FrameworkBundle\Routing\Router;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Routing\RouterInterface;

class OauthService
{

    /**
     * @var array
     */
    protected $configProvider;

    /**
     * @var Router|RouterInterface
     */
    protected $router;

    /**
     * @var ContainerInterface
     */
    protected $container;

    public function __construct(RouterInterface $router)
    {
        $this->router = $router;
        $this->setConfigProvider();
    }

    /**
     * @param mixed|null $data
     *
     * @return mixed|void
     */
    public function getIdentity($data, ?string $oauth)
    {
        $entity = null;
        if (is_null($oauth)) {
            return;
        }

        switch ($oauth) {
            case 'gitlab':
            case 'github':
            case 'discord':
                if (!isset($data['id'])) {
                    return;
                }

                $entity = $data['id'];

                break;
            case 'google':
                if (!isset($data['sub'])) {
                    return;
                }

                $entity = $data['sub'];

                break;
            case 'bitbucket':
                if (!isset($data['uuid'])) {
                    return;
                }

                $entity = $data['uuid'];

                break;
            default:
                break;
        }

        return $entity;
    }

    /**
     * @return GenericProviderLib|void
     */
    public function setProvider(?string $clientName)
    {
        if (is_null($clientName)) {
            return;
        }

        if (isset($this->configProvider[$clientName])) {
            return $this->initProvider($clientName);
        }
    }

    public function getActivedProvider(?string $clientName): bool
    {
        if (is_null($clientName)) {
            return false;
        }

        return array_key_exists($clientName, $this->configProvider);
    }

    protected function setConfigProvider(): void
    {
        $this->configProvider = [
            'gitlab'    => [
                'params'         => [
                    'urlAuthorize'            => 'https://gitlab.com/oauth/authorize',
                    'urlAccessToken'          => 'https://gitlab.com/oauth/token',
                    'urlResourceOwnerDetails' => 'https://gitlab.com/api/v4/user',
                ],
                'redirect'       => 1,
                'scopeseparator' => ' ',
                'scopes'         => ['read_user'],
            ],
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

    protected function initProvider(string $clientName): GenericProviderLib
    {
        $config = (isset($this->configProvider[$clientName])) ? $this->configProvider[$clientName] : [];
        if (isset($config['redirect'])) {
            $config['params']['redirectUri'] = $this->router->generate(
                'connect_check',
                ['oauthCode' => $clientName],
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
}
