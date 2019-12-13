<?php

namespace spec\Labstag\Repository;

use Labstag\Repository\EmailRepository;
use PhpSpec\ObjectBehavior;

class EmailRepositorySpec extends ObjectBehavior
{
    public function it_is_initializable()
    {
        $this->shouldHaveType(EmailRepository::class);
    }
}
