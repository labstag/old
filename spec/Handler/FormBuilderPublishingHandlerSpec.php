<?php

namespace spec\Labstag\Handler;

use Labstag\Handler\FormBuilderPublishingHandler;
use PhpSpec\ObjectBehavior;

class FormBuilderPublishingHandlerSpec extends ObjectBehavior
{
    public function it_is_initializable()
    {
        $this->shouldHaveType(FormBuilderPublishingHandler::class);
    }
}
