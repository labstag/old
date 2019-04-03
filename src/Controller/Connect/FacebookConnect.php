<?php

namespace App\Controller\Connect;

use KnpU\OAuth2ClientBundle\Client\ClientRegistry;
use App\Lib\ConnectAbstractControllerLib;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class FacebookConnect extends ConnectAbstractControllerLib
{
    /**
     * Link to this controller to start the "connect" process
     *
     * @Route("/connect/facebook", name="connect_facebook_start")
     */
    public function connectAction(ClientRegistry $clientRegistry)
    {
        return $clientRegistry
            ->getClient('facebook_main') // key used in config/packages/knpu_oauth2_client.yaml
            ->redirect(
                [
	    	    'public_profile', 'email' // the scopes you want to access
                ]
            );
	}

    /**
     * After going to Facebook, you're redirected back here
     * because this is the "redirect_route" you configured
     * in config/packages/knpu_oauth2_client.yaml
     *
     * @Route("/connect/facebook/check", name="connect_facebook_check")
     */
    public function connectCheckAction(Request $request, ClientRegistry $clientRegistry)
    {
        $this->connectCheck($clientRegistry, "facebook_main");
    }
}