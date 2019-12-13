<?php

namespace spec\Labstag\Repository;

use Labstag\Repository\TagsRepository;
use PhpSpec\ObjectBehavior;

class TagsRepositorySpec extends ObjectBehavior
{
    public function it_is_initializable()
    {
        $this->shouldHaveType(TagsRepository::class);
    }
}
