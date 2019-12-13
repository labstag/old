<?php

namespace spec\Labstag\Repository;

use Labstag\Repository\OauthConnectUserRepository;
use PhpSpec\ObjectBehavior;

class OauthConnectUserRepositorySpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType(OauthConnectUserRepository::class);
    }
}
