<?php

namespace spec\Labstag\FormType;

use Labstag\FormType\FieldsetType;
use PhpSpec\ObjectBehavior;

class FieldsetTypeSpec extends ObjectBehavior
{
    public function it_is_initializable()
    {
        $this->shouldHaveType(FieldsetType::class);
    }
}
