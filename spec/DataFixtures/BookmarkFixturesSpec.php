<?php

namespace spec\Labstag\DataFixtures;

use Labstag\DataFixtures\BookmarkFixtures;
use PhpSpec\ObjectBehavior;

class BookmarkFixturesSpec extends ObjectBehavior
{
    public function it_is_initializable()
    {
        $this->shouldHaveType(BookmarkFixtures::class);
    }
}
