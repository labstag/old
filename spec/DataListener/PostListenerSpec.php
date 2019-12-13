<?php

namespace spec\Labstag\DataListener;

use Labstag\DataListener\PostListener;
use PhpSpec\ObjectBehavior;

class PostListenerSpec extends ObjectBehavior
{
    public function it_is_initializable()
    {
        $this->shouldHaveType(PostListener::class);
    }
}
