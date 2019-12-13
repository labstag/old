<?php

namespace spec\Labstag\DataListener;

use Labstag\DataListener\UserListener;
use PhpSpec\ObjectBehavior;

class UserListenerSpec extends ObjectBehavior
{
    public function it_is_initializable()
    {
        $this->shouldHaveType(UserListener::class);
    }
}
