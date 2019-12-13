<?php

namespace spec\Labstag\Entity;

use Labstag\Entity\User;
use PhpSpec\ObjectBehavior;

class UserSpec extends ObjectBehavior
{
    public function it_is_initializable()
    {
        $this->shouldHaveType(User::class);
    }
}
