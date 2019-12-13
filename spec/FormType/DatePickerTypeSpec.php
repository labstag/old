<?php

namespace spec\Labstag\FormType;

use Labstag\FormType\DatePickerType;
use PhpSpec\ObjectBehavior;

class DatePickerTypeSpec extends ObjectBehavior
{
    public function it_is_initializable()
    {
        $this->shouldHaveType(DatePickerType::class);
    }
}
