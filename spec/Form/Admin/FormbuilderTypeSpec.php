<?php

namespace spec\Labstag\Form\Admin;

use Labstag\Form\Admin\FormbuilderType;
use PhpSpec\ObjectBehavior;

class FormbuilderTypeSpec extends ObjectBehavior
{
    public function it_is_initializable()
    {
        $this->shouldHaveType(FormbuilderType::class);
    }
}
