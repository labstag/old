<?php

namespace spec\Labstag\Entity;

use Labstag\Entity\Email;
use PhpSpec\ObjectBehavior;

class EmailSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType(Email::class);
    }
}
