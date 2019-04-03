<?php

namespace App\Lib;

use KnpU\OAuth2ClientBundle\Client\ClientRegistry;
use League\OAuth2\Client\Provider\Exception\IdentityProviderException;

abstract class ConnectAbstractControllerLib extends AbstractControllerLib
{
    protected function connectCheck(
        ClientRegistry $clientRegistry,
        string $clientName
    )
    {
        // ** if you want to *authenticate* the user, then
        // leave this method blank and create a Guard authenticator
        // (read below)

        /** @var \KnpU\OAuth2ClientBundle\Client\Provider\GithubClient $client */
        $client = $clientRegistry->getClient($clientName);

        try {
            // the exact class depends on which provider you're using
            /** @var \League\OAuth2\Client\Provider\GithubUser $user */
            $user = $client->fetchUser();

            // do something with all this new power!
	        // e.g. $name = $user->getFirstName();
            dump($user);
            return;
            // ...
        } catch (IdentityProviderException $e) {
            // something went wrong!
            // probably you should return the reason to the user
            dump($e->getMessage());
            return;
        }
    }
}
