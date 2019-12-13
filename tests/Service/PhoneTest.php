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
        $json    = $service->verif('0701010101', 'fr');
        $this->assertFalse($json);
    }
}
