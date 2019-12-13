<?php

namespace spec\Labstag\Form\Admin;

use Labstag\Form\Admin\TemplatesType;
use PhpSpec\ObjectBehavior;

class TemplatesTypeSpec extends ObjectBehavior
{
    public function it_is_initializable()
    {
        $this->shouldHaveType(TemplatesType::class);
    }
}
