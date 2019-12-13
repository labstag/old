<?php

namespace spec\Labstag\Security;

use Labstag\Security\OauthAuthenticator;
use PhpSpec\ObjectBehavior;

class OauthAuthenticatorSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType(OauthAuthenticator::class);
    }
}
