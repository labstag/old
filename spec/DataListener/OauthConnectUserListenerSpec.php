<?php

namespace spec\Labstag\DataListener;

use Labstag\DataListener\OauthConnectUserListener;
use PhpSpec\ObjectBehavior;

class OauthConnectUserListenerSpec extends ObjectBehavior
{
    public function it_is_initializable()
    {
        $this->shouldHaveType(OauthConnectUserListener::class);
    }
}
