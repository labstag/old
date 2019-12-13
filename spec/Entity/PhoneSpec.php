<?php

namespace spec\Labstag\Entity;

use Labstag\Entity\Phone;
use PhpSpec\ObjectBehavior;

class PhoneSpec extends ObjectBehavior
{
    public function it_is_initializable()
    {
        $this->shouldHaveType(Phone::class);
    }
}
