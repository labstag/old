<?php

namespace spec\Labstag\Form\Admin;

use Labstag\Form\Admin\HistoryType;
use PhpSpec\ObjectBehavior;

class HistoryTypeSpec extends ObjectBehavior
{
    public function it_is_initializable()
    {
        $this->shouldHaveType(HistoryType::class);
    }
}
