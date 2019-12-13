<?php

namespace spec\Labstag\Handler;

use Labstag\Handler\TagsPublishingHandler;
use PhpSpec\ObjectBehavior;

class TagsPublishingHandlerSpec extends ObjectBehavior
{
    public function it_is_initializable()
    {
        $this->shouldHaveType(TagsPublishingHandler::class);
    }
}
