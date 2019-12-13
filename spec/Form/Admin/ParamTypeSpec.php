<?php

namespace spec\Labstag\Form\Admin;

use Labstag\Form\Admin\ParamType;
use PhpSpec\ObjectBehavior;

class ParamTypeSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType(ParamType::class);
    }
}
