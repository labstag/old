<?php

namespace spec\Labstag\Repository;

use Labstag\Repository\PhoneRepository;
use PhpSpec\ObjectBehavior;

class PhoneRepositorySpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType(PhoneRepository::class);
    }
}
