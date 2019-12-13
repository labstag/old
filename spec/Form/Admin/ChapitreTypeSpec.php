<?php

namespace spec\Labstag\Form\Admin;

use Labstag\Form\Admin\ChapitreType;
use PhpSpec\ObjectBehavior;

class ChapitreTypeSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType(ChapitreType::class);
    }
}
