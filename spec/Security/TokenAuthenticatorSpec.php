<?php

namespace spec\Labstag\Security;

use Labstag\Security\TokenAuthenticator;
use PhpSpec\ObjectBehavior;

class TokenAuthenticatorSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType(TokenAuthenticator::class);
    }
}
