<?php

namespace spec\Labstag\DataListener;

use Labstag\DataListener\ConfigurationListener;
use PhpSpec\ObjectBehavior;

class ConfigurationListenerSpec extends ObjectBehavior
{
    public function it_is_initializable()
    {
        $this->shouldHaveType(ConfigurationListener::class);
    }
}
