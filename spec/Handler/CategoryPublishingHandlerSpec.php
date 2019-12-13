<?php

namespace spec\Labstag\Handler;

use Labstag\Handler\CategoryPublishingHandler;
use PhpSpec\ObjectBehavior;

class CategoryPublishingHandlerSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType(CategoryPublishingHandler::class);
    }
}
