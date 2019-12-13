<?php

namespace spec\Labstag\DataFixtures;

use Labstag\DataFixtures\HistoryFixtures;
use PhpSpec\ObjectBehavior;

class HistoryFixturesSpec extends ObjectBehavior
{
    public function it_is_initializable()
    {
        $this->shouldHaveType(HistoryFixtures::class);
    }
}
