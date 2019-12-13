<?php

namespace spec\Labstag\Form\Admin;

use Labstag\Form\Admin\UserType;
use PhpSpec\ObjectBehavior;

class UserTypeSpec extends ObjectBehavior
{
    public function it_is_initializable()
    {
        $this->shouldHaveType(UserType::class);
    }
}
