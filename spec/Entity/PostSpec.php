<?php

namespace spec\Labstag\Entity;

use Labstag\Entity\Post;
use PhpSpec\ObjectBehavior;

class PostSpec extends ObjectBehavior
{
    public function it_is_initializable()
    {
        $this->shouldHaveType(Post::class);
    }
}
