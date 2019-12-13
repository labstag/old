<?php

namespace spec\Labstag\Form\Admin;

use Labstag\Form\Admin\TagsType;
use PhpSpec\ObjectBehavior;

class TagsTypeSpec extends ObjectBehavior
{
    public function it_is_initializable()
    {
        $this->shouldHaveType(TagsType::class);
    }
}
