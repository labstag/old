<?php

namespace spec\Labstag\Handler;

use Labstag\Handler\UserPublishingHandler;
use PhpSpec\ObjectBehavior;

class UserPublishingHandlerSpec extends ObjectBehavior
{
    public function it_is_initializable()
    {
        $this->shouldHaveType(UserPublishingHandler::class);
    }
}
