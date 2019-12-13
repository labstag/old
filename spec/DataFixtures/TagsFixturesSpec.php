<?php

namespace spec\Labstag\DataFixtures;

use Labstag\DataFixtures\TagsFixtures;
use PhpSpec\ObjectBehavior;

class TagsFixturesSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType(TagsFixtures::class);
    }
}
