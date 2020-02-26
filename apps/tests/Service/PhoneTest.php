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
    public function testPhone(): void
    {
        /** @var PhoneService */
        $service = self::$container->get(PhoneService::class);
        $data    = $service->verif('0606060606', 'FR');
        $this->assertTrue(is_array($data));
        $this->assertSame('+33606060606', $data['format']['e164']);
        $this->assertSame('Europe/Paris', $data['timezones'][0]);
    }
}
