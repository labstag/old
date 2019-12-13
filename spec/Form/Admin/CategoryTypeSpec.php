<?php

namespace spec\Labstag\Form\Admin;

use Labstag\Form\Admin\CategoryType;
use PhpSpec\ObjectBehavior;

class CategoryTypeSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType(CategoryType::class);
    }
}
