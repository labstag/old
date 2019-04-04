<?php

namespace App\Controller\Connect;

use App\Lib\ConnectAbstractControllerLib;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class MicrosoftConnect extends ConnectAbstractControllerLib
{
    /**
     * Link to this controller to start the "connect" process
     *
     * @Route("/connect/microsoft", name="connect_microsoft_start")
     */
    public function connectAction()
    {
        return $this->connectRedirect('microsoft');
	}

    /**
     * After going to Github, you're redirected back here
     * because this is the "redirect_route" you configured
     * in config/packages/knpu_oauth2_client.yaml
     *
     * @Route("/connect/microsoft/check", name="connect_microsoft_check")
     */
    public function connectCheckAction(Request $request)
    {
        $this->connectCheck("microsoft");
    }
}