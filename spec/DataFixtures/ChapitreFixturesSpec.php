<?php

namespace spec\Labstag\DataFixtures;

use Labstag\DataFixtures\ChapitreFixtures;
use PhpSpec\ObjectBehavior;

class ChapitreFixturesSpec extends ObjectBehavior
{
    public function it_is_initializable()
    {
        $this->shouldHaveType(ChapitreFixtures::class);
    }
}
