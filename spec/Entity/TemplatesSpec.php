<?php

namespace spec\Labstag\Entity;

use Labstag\Entity\Templates;
use PhpSpec\ObjectBehavior;

class TemplatesSpec extends ObjectBehavior
{
    public function it_is_initializable()
    {
        $this->shouldHaveType(Templates::class);
    }
}
