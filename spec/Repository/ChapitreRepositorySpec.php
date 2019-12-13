<?php

namespace spec\Labstag\Repository;

use Labstag\Repository\ChapitreRepository;
use PhpSpec\ObjectBehavior;

class ChapitreRepositorySpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType(ChapitreRepository::class);
    }
}
