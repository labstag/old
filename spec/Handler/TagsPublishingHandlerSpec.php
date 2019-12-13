<?php

namespace spec\Labstag\Handler;

use Labstag\Handler\TagsPublishingHandler;
use PhpSpec\ObjectBehavior;

class TagsPublishingHandlerSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType(TagsPublishingHandler::class);
    }
}
