<?php

namespace spec\Labstag\Handler;

use Labstag\Handler\FormbuilderPublishingHandler;
use PhpSpec\ObjectBehavior;

class FormbuilderPublishingHandlerSpec extends ObjectBehavior
{
    public function it_is_initializable()
    {
        $this->shouldHaveType(FormbuilderPublishingHandler::class);
    }
}
