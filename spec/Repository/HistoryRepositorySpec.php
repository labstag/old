<?php

namespace spec\Labstag\Repository;

use Labstag\Repository\HistoryRepository;
use PhpSpec\ObjectBehavior;

class HistoryRepositorySpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType(HistoryRepository::class);
    }
}
