<?php

namespace App\Controller\Connect;

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
    public function connectAction()
    {
        return $this->connectRedirect('facebook');
	}

    /**
     * After going to Facebook, you're redirected back here
     * because this is the "redirect_route" you configured
     * in config/packages/knpu_oauth2_client.yaml
     *
     * @Route("/connect/facebook/check", name="connect_facebook_check")
     */
    public function connectCheckAction(Request $request)
    {
        $this->connectCheck("facebook");
    }
}