<?php

namespace spec\Labstag\Form\Security;

use Labstag\Form\Security\LostPasswordType;
use PhpSpec\ObjectBehavior;

class LostPasswordTypeSpec extends ObjectBehavior
{
    public function it_is_initializable()
    {
        $this->shouldHaveType(LostPasswordType::class);
    }
}
