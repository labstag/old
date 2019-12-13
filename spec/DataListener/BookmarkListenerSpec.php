<?php

namespace spec\Labstag\DataListener;

use Labstag\DataListener\BookmarkListener;
use PhpSpec\ObjectBehavior;

class BookmarkListenerSpec extends ObjectBehavior
{
    public function it_is_initializable()
    {
        $this->shouldHaveType(BookmarkListener::class);
    }
}
