<?php

namespace Labstag\Controller;

use Labstag\Lib\ControllerLib;
use Symfony\Component\Routing\Annotation\Route;

class CheckController extends ControllerLib
{
    /**
     * @Route("/check/email/{id}", name="check-email")
     */
    public function email($id)
    {
        return $this->json([$id]);
    }
}
