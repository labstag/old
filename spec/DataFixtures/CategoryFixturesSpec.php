<?php

namespace spec\Labstag\DataFixtures;

use Labstag\DataFixtures\CategoryFixtures;
use PhpSpec\ObjectBehavior;

class CategoryFixturesSpec extends ObjectBehavior
{
    public function it_is_initializable()
    {
        $this->shouldHaveType(CategoryFixtures::class);
    }
}
