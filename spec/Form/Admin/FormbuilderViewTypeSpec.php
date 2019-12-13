<?php

namespace spec\Labstag\Form\Admin;

use Labstag\Form\Admin\FormbuilderViewType;
use PhpSpec\ObjectBehavior;

class FormbuilderViewTypeSpec extends ObjectBehavior
{
    public function it_is_initializable()
    {
        $this->shouldHaveType(FormbuilderViewType::class);
    }
}
