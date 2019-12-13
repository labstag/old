<?php

namespace spec\Labstag\Form\Admin;

use Labstag\Form\Admin\ConfigurationType;
use PhpSpec\ObjectBehavior;

class ConfigurationTypeSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType(ConfigurationType::class);
    }
}
