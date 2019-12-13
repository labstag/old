<?php

namespace spec\Labstag\Handler;

use Labstag\Handler\TemplatesPublishingHandler;
use PhpSpec\ObjectBehavior;

class TemplatesPublishingHandlerSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType(TemplatesPublishingHandler::class);
    }
}
