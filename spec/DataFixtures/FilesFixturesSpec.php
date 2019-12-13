<?php

namespace spec\Labstag\DataFixtures;

use Labstag\DataFixtures\FilesFixtures;
use PhpSpec\ObjectBehavior;

class FilesFixturesSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType(FilesFixtures::class);
    }
}
