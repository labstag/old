<?php

namespace spec\Labstag\DataFixtures;

use Labstag\DataFixtures\PostFixtures;
use PhpSpec\ObjectBehavior;

class PostFixturesSpec extends ObjectBehavior
{
    public function it_is_initializable()
    {
        $this->shouldHaveType(PostFixtures::class);
    }
}
