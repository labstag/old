<?php

namespace spec\Labstag\DataListener;

use Labstag\DataListener\EmailListener;
use PhpSpec\ObjectBehavior;

class EmailListenerSpec extends ObjectBehavior
{
    public function it_is_initializable()
    {
        $this->shouldHaveType(EmailListener::class);
    }
}
