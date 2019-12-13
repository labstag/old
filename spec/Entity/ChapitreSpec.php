<?php

namespace spec\Labstag\Entity;

use Labstag\Entity\Chapitre;
use PhpSpec\ObjectBehavior;

class ChapitreSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType(Chapitre::class);
    }
}
