<?php

namespace spec\Labstag\Handler;

use Labstag\Handler\HistoryPublishingHandler;
use PhpSpec\ObjectBehavior;

class HistoryPublishingHandlerSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType(HistoryPublishingHandler::class);
    }
}
