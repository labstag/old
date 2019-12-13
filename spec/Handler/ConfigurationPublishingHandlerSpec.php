<?php

namespace spec\Labstag\Handler;

use Labstag\Handler\ConfigurationPublishingHandler;
use PhpSpec\ObjectBehavior;

class ConfigurationPublishingHandlerSpec extends ObjectBehavior
{
    public function it_is_initializable()
    {
        $this->shouldHaveType(ConfigurationPublishingHandler::class);
    }
}
