<?php

namespace spec\Labstag\FormType;

use Labstag\FormType\OuiNonType;
use PhpSpec\ObjectBehavior;

class OuiNonTypeSpec extends ObjectBehavior
{
    public function it_is_initializable()
    {
        $this->shouldHaveType(OuiNonType::class);
    }
}
