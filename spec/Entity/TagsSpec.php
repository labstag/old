<?php

namespace spec\Labstag\Entity;

use Labstag\Entity\Tags;
use PhpSpec\ObjectBehavior;

class TagsSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType(Tags::class);
    }
}
