<?php

namespace Labstag\Lib;

use League\OAuth2\Client\Provider\Github;
use League\OAuth2\Client\Provider\Google;
use Wohali\OAuth2\Client\Provider\Discord;
use Luchianenco\OAuth2\Client\Provider\Amazon;
use Stevenmaguire\OAuth2\Client\Provider\Bitbucket;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

abstract class ConnectAbstractControllerLib extends AbstractControllerLib
{

    protected function initProviderBitbucket()
    {
        $provider = new Bitbucket(
            [
                'clientId'     => getenv('OAUTH_BITBUCKET_ID'),
                'clientSecret' => getenv('OAUTH_BITBUCKET_SECRET'),
                'redirectUri'  => $this->generateUrl('connect_check', ['oauthCode' => 'bitbucket'], UrlGeneratorInterface::ABSOLUTE_URL),
            ]
        );

        return $provider;
    }

    protected function initProviderGithub()
    {
        $provider = new Github(
            [
                'clientId'     => getenv('OAUTH_GITHUB_ID'),
                'clientSecret' => getenv('OAUTH_GITHUB_SECRET'),
                'redirectUri'  => $this->generateUrl('connect_check', ['oauthCode' => 'github'], UrlGeneratorInterface::ABSOLUTE_URL),
            ]
        );

        return $provider;
    }

    protected function initProviderGoogle()
    {
        $provider = new Google(
            [
                'clientId'     => getenv('OAUTH_GOOGLE_ID'),
                'clientSecret' => getenv('OAUTH_GOOGLE_SECRET'),
                'redirectUri'  => $this->generateUrl('connect_check', ['oauthCode' => 'google'], UrlGeneratorInterface::ABSOLUTE_URL),
            ]
        );

        return $provider;
    }

    protected function initProviderDiscord()
    {
        $provider = new Discord(
            [
                'clientId'     => getenv('OAUTH_DISCORD_ID'),
                'clientSecret' => getenv('OAUTH_DISCORD_SECRET'),
                'redirectUri'  => $this->generateUrl('connect_check', ['oauthCode' => 'discord'], UrlGeneratorInterface::ABSOLUTE_URL),
            ]
        );

        return $provider;
    }

    protected function initProviderAmazon()
    {
        $provider = new Amazon(
            [
                'clientId'     => getenv('OAUTH_AMAZON_ID'),
                'clientSecret' => getenv('OAUTH_AMAZON_SECRET'),
                'redirectUri' => $this->generateUrl('connect_check', ['oauthCode' => 'amazon'], UrlGeneratorInterface::ABSOLUTE_URL),
            ]
        );

        return $provider;
    }

    protected function setProvider($clientName)
    {
        if ('bitbucket' == $clientName) {
            return $this->initProviderBitbucket();
        }

        if ('github' == $clientName) {
            return $this->initProviderGithub();
        }

        if ('google' == $clientName) {
            return $this->initProviderGoogle();
        }

        if ('discord' == $clientName) {
            return $this->initProviderDiscord();
        }

        if ('amazon' == $clientName) {
            return $this->initProviderAmazon();
        }

        return null;
    }

    protected function connectRedirect(string $clientName)
    {
        $provider = $this->setProvider($clientName);

        if (is_null($provider)) {
            return;
        }

        $authUrl = $provider->getAuthorizationUrl();
        $_SESSION['oauth2state'] = $provider->getState();
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
                'code' => $_GET['code']
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
