<?php

namespace spec\Labstag\Handler;

use Labstag\Handler\TemplatesPublishingHandler;
use PhpSpec\ObjectBehavior;

class TemplatesPublishingHandlerSpec extends ObjectBehavior
{
    public function it_is_initializable()
    {
        $this->shouldHaveType(TemplatesPublishingHandler::class);
    }
}
