<?php

namespace spec\Labstag\Form\Admin;

use Labstag\Form\Admin\PositionType;
use PhpSpec\ObjectBehavior;

class PositionTypeSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType(PositionType::class);
    }
}
