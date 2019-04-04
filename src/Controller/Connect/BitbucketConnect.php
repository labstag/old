<?php

namespace App\Controller\Connect;

use App\Lib\ConnectAbstractControllerLib;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class BitbucketConnect extends ConnectAbstractControllerLib
{
    /**
     * Link to this controller to start the "connect" process
     *
     * @Route("/connect/bitbucket", name="connect_bitbucket_start")
     */
    public function connectAction()
    {
        return $this->connectRedirect('bitbucket');
	}

    /**
     * After going to Facebook, you're redirected back here
     * because this is the "redirect_route" you configured
     * in config/packages/knpu_oauth2_client.yaml
     *
     * @Route("/connect/bitbucket/check", name="connect_bitbucket_check")
     */
    public function connectCheckAction(Request $request)
    {
        $this->connectCheck("bitbucket");
    }
}