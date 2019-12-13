<?php

namespace spec\Labstag\DataListener;

use Labstag\DataListener\ConfigurationListener;
use PhpSpec\ObjectBehavior;

class ConfigurationListenerSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType(ConfigurationListener::class);
    }
}
