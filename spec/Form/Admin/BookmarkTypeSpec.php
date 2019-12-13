<?php

namespace spec\Labstag\Form\Admin;

use Labstag\Form\Admin\BookmarkType;
use PhpSpec\ObjectBehavior;

class BookmarkTypeSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType(BookmarkType::class);
    }
}
