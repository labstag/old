<?php

namespace spec\Labstag\Repository;

use Labstag\Repository\PostRepository;
use PhpSpec\ObjectBehavior;

class PostRepositorySpec extends ObjectBehavior
{
    public function it_is_initializable()
    {
        $this->shouldHaveType(PostRepository::class);
    }
}
