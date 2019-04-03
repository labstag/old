<?php

namespace App\Controller\Connect;

use KnpU\OAuth2ClientBundle\Client\ClientRegistry;
use App\Lib\ConnectAbstractControllerLib;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class GoogleConnect extends ConnectAbstractControllerLib
{
    /**
     * Link to this controller to start the "connect" process
     *
     * @Route("/connect/google", name="connect_google_start")
     */
    public function connectAction(ClientRegistry $clientRegistry)
    {
        return $clientRegistry
            ->getClient('google_main') // key used in config/packages/knpu_oauth2_client.yaml
            ->redirect(
                [
	    	    'public_profile', 'email' // the scopes you want to access
                ]
            );
	}

    /**
     * After going to Google, you're redirected back here
     * because this is the "redirect_route" you configured
     * in config/packages/knpu_oauth2_client.yaml
     *
     * @Route("/connect/google/check", name="connect_google_check")
     */
    public function connectCheckAction(Request $request, ClientRegistry $clientRegistry)
    {
        $this->connectCheck($clientRegistry, "google_main");
    }
}