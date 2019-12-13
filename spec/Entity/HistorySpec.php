<?php

namespace spec\Labstag\Entity;

use Labstag\Entity\History;
use PhpSpec\ObjectBehavior;

class HistorySpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType(History::class);
    }
}
