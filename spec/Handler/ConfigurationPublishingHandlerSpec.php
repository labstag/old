<?php

namespace spec\Labstag\Handler;

use Labstag\Handler\ConfigurationPublishingHandler;
use PhpSpec\ObjectBehavior;

class ConfigurationPublishingHandlerSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType(ConfigurationPublishingHandler::class);
    }
}
