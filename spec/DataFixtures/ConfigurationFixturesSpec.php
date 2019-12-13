<?php

namespace spec\Labstag\DataFixtures;

use Labstag\DataFixtures\ConfigurationFixtures;
use PhpSpec\ObjectBehavior;

class ConfigurationFixturesSpec extends ObjectBehavior
{
    public function it_is_initializable()
    {
        $this->shouldHaveType(ConfigurationFixtures::class);
    }
}
