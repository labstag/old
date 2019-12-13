<?php

namespace spec\Labstag\DataFixtures;

use Labstag\DataFixtures\UserFixtures;
use PhpSpec\ObjectBehavior;

class UserFixturesSpec extends ObjectBehavior
{
    public function it_is_initializable()
    {
        $this->shouldHaveType(UserFixtures::class);
    }
}
