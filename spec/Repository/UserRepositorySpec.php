<?php

namespace spec\Labstag\Repository;

use Labstag\Repository\UserRepository;
use PhpSpec\ObjectBehavior;

class UserRepositorySpec extends ObjectBehavior
{
    public function it_is_initializable()
    {
        $this->shouldHaveType(UserRepository::class);
    }
}
