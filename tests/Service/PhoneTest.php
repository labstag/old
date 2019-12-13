<?php

namespace Labstag\Tests\Service;

use Labstag\Lib\ServiceTestLib;
use Labstag\Service\PhoneService;

class PhoneTest extends ServiceTestLib
{


    public function testPhone()
    {
        /** @var PhoneService */
        $service = self::$container->get(PhoneService::class);
        $json    = $service->verif('0701010101', 'fr');
    }
}