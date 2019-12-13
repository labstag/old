<?php

namespace spec\Labstag\DataFixtures;

use Labstag\DataFixtures\TemplatesFixtures;
use PhpSpec\ObjectBehavior;

class TemplatesFixturesSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType(TemplatesFixtures::class);
    }
}
