<?php

namespace spec\Labstag\Service;

use Labstag\Service\PhoneService;
use PhpSpec\ObjectBehavior;

class PhoneServiceSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType(PhoneService::class);
    }
}
