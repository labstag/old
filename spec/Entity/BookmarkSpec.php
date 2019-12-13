<?php

namespace spec\Labstag\Entity;

use Labstag\Entity\Bookmark;
use PhpSpec\ObjectBehavior;

class BookmarkSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType(Bookmark::class);
    }
}
