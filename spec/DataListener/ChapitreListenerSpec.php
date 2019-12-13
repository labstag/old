<?php

namespace spec\Labstag\DataListener;

use Labstag\DataListener\ChapitreListener;
use PhpSpec\ObjectBehavior;

class ChapitreListenerSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType(ChapitreListener::class);
    }
}
