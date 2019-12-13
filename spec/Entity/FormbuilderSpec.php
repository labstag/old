<?php

namespace spec\Labstag\Entity;

use Labstag\Entity\Formbuilder;
use PhpSpec\ObjectBehavior;

class FormbuilderSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType(Formbuilder::class);
    }
}
