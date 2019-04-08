<?php

namespace Labstag\Controller;

use Labstag\Lib\ConnectAbstractControllerLib;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class ConnectController extends ConnectAbstractControllerLib
{
    /**
     * Link to this controller to start the "connect" process.
     *
     * @Route("/connect/{oauthCode}", name="connect_start")
     */
    public function connectAction(Request $request, string $oauthCode)
    {
        return $this->connectRedirect($request, $oauthCode);
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
        return $this->connectCheck($request, $oauthCode);
    }
}
