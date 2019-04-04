<?php

namespace App\Controller\Connect;

use App\Lib\ConnectAbstractControllerLib;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class GitLabConnect extends ConnectAbstractControllerLib
{
    /**
     * Link to this controller to start the "connect" process
     *
     * @Route("/connect/gitlab", name="connect_gitlab_start")
     */
    public function connectAction()
    {
        return $this->connectRedirect('gitlab');
	}

    /**
     * After going to Github, you're redirected back here
     * because this is the "redirect_route" you configured
     * in config/packages/knpu_oauth2_client.yaml
     *
     * @Route("/connect/gitlab/check", name="connect_gitlab_check")
     */
    public function connectCheckAction(Request $request)
    {
        $this->connectCheck("gitlab");
    }
}