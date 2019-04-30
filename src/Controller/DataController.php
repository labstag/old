<?php

namespace Labstag\Controller;

use Labstag\Lib\ControllerLib;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;

class DataController extends ControllerLib
{

    /**
     * @Route("/datatableslang", name="data_datatableslang")
     */
    public function datatableslang(): JsonResponse
    {
        $file = 'build/i18n-datatables/French.lang';
        $content = file_get_contents($file);
        return $this->json($content);
    }
}
