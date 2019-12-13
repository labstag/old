<?php

namespace spec\Labstag\Entity;

use Labstag\Entity\OauthConnectUser;
use PhpSpec\ObjectBehavior;

class OauthConnectUserSpec extends ObjectBehavior
{
    public function it_is_initializable()
    {
        $this->shouldHaveType(OauthConnectUser::class);
    }
}
