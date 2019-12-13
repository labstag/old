<?php

namespace spec\Labstag\DataListener;

use Labstag\DataListener\PhoneListener;
use PhpSpec\ObjectBehavior;

class PhoneListenerSpec extends ObjectBehavior
{
    public function it_is_initializable()
    {
        $this->shouldHaveType(PhoneListener::class);
    }
}
