<?php

namespace spec\Labstag\Repository;

use Labstag\Repository\TemplatesRepository;
use PhpSpec\ObjectBehavior;

class TemplatesRepositorySpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType(TemplatesRepository::class);
    }
}
