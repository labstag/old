<?php

namespace spec\Labstag\Handler;

use Labstag\Handler\ChapitrePublishingHandler;
use PhpSpec\ObjectBehavior;

class ChapitrePublishingHandlerSpec extends ObjectBehavior
{
    public function it_is_initializable()
    {
        $this->shouldHaveType(ChapitrePublishingHandler::class);
    }
}
