<?php

namespace spec\Labstag\DataFixtures;

use Labstag\DataFixtures\UserFixtures;
use PhpSpec\ObjectBehavior;

class UserFixturesSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType(UserFixtures::class);
    }
}
