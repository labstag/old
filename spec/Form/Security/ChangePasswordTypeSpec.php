<?php

namespace spec\Labstag\Form\Security;

use Labstag\Form\Security\ChangePasswordType;
use PhpSpec\ObjectBehavior;

class ChangePasswordTypeSpec extends ObjectBehavior
{
    public function it_is_initializable()
    {
        $this->shouldHaveType(ChangePasswordType::class);
    }
}
