<?php

namespace spec\Labstag\FormType;

use Labstag\FormType\WysiwygType;
use PhpSpec\ObjectBehavior;

class WysiwygTypeSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType(WysiwygType::class);
    }
}
