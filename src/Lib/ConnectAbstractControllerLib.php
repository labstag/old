<?php

namespace App\Lib;

use KnpU\OAuth2ClientBundle\Client\ClientRegistry;
use Symfony\Component\DependencyInjection\ContainerInterface;
use League\OAuth2\Client\Provider\Exception\IdentityProviderException;

abstract class ConnectAbstractControllerLib extends AbstractControllerLib
{

    /**
     * @var ClientRegistry
     */
    private $clientRegistry;

    public function __construct(
        ContainerInterface $container,
        ClientRegistry $clientRegistry
    )
    {
        $this->clientRegistry = $clientRegistry;
        parent::__construct($container);
    }

    protected function connectRedirect(string $clientName)
    {
        $service  = $this->clientRegistry->getClient($clientName.'_main');
        $redirect = $service->redirect(
            [
            'public_profile', 'email' // the scopes you want to access
            ]
        );

        return $redirect;
    }

    protected function connectCheck(string $clientName)
    {
        // ** if you want to *authenticate* the user, then
        // leave this method blank and create a Guard authenticator
        // (read below)

        /** @var \KnpU\OAuth2ClientBundle\Client\Provider\GithubClient $client */
        $client = $this->clientRegistry->getClient($clientName.'_main');

        try {
            // the exact class depends on which provider you're using
            /** @var \League\OAuth2\Client\Provider\GithubUser $user */
            $user = $client->fetchUser();

            // do something with all this new power!
	        // e.g. $name = $user->getFirstName();
            dump($user);
            exit();
            return;
            // ...
        } catch (IdentityProviderException $e) {
            // something went wrong!
            // probably you should return the reason to the user
            dump($e->getMessage());
            exit();
            return;
        }
    }
}
