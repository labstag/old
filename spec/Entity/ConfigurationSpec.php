<?php

namespace spec\Labstag\Entity;

use Labstag\Entity\Configuration;
use PhpSpec\ObjectBehavior;

class ConfigurationSpec extends ObjectBehavior
{
    public function it_is_initializable()
    {
        $this->shouldHaveType(Configuration::class);
    }
}
