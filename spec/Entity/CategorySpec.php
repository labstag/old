<?php

namespace spec\Labstag\Entity;

use Labstag\Entity\Category;
use PhpSpec\ObjectBehavior;

class CategorySpec extends ObjectBehavior
{
    public function it_is_initializable()
    {
        $this->shouldHaveType(Category::class);
    }
}
