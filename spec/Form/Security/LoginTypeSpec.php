<?php

namespace spec\Labstag\Form\Security;

use Labstag\Form\Security\LoginType;
use PhpSpec\ObjectBehavior;

class LoginTypeSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType(LoginType::class);
    }
}
