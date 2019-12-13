<?php

namespace spec\Labstag\Repository;

use Labstag\Repository\PhoneRepository;
use PhpSpec\ObjectBehavior;

class PhoneRepositorySpec extends ObjectBehavior
{
    public function it_is_initializable()
    {
        $this->shouldHaveType(PhoneRepository::class);
    }
}
