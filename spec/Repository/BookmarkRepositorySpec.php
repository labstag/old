<?php

namespace spec\Labstag\Repository;

use Labstag\Repository\BookmarkRepository;
use PhpSpec\ObjectBehavior;

class BookmarkRepositorySpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType(BookmarkRepository::class);
    }
}
