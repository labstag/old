<?php

namespace Labstag\Tests\Service;

use Labstag\Lib\ServiceTestLib;
use Labstag\Service\PhoneService;

/**
 * @internal
 * @coversNothing
 */
class PhoneTest extends ServiceTestLib
{
    public function testPhone()
    {
        /** @var PhoneService */
        $service = self::$container->get(PhoneService::class);
        $data    = $service->verif('0606060606', 'FR');
        $this->assertTrue(is_array($data));
        $this->assertTrue('FR' == $data['country']);
        $this->assertTrue('+33606060606' == $data['international']);
    }
}
