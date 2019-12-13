<?php

namespace spec\Labstag\Handler;

use Labstag\Handler\HistoryPublishingHandler;
use PhpSpec\ObjectBehavior;

class HistoryPublishingHandlerSpec extends ObjectBehavior
{
    public function it_is_initializable()
    {
        $this->shouldHaveType(HistoryPublishingHandler::class);
    }
}
