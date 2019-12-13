<?php

namespace spec\Labstag\Handler;

use Labstag\Handler\BookmarkPublishingHandler;
use PhpSpec\ObjectBehavior;

class BookmarkPublishingHandlerSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType(BookmarkPublishingHandler::class);
    }
}
