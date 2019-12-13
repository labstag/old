<?php

namespace spec\Labstag\Repository;

use Labstag\Repository\FormbuilderRepository;
use PhpSpec\ObjectBehavior;

class FormbuilderRepositorySpec extends ObjectBehavior
{
    public function it_is_initializable()
    {
        $this->shouldHaveType(FormbuilderRepository::class);
    }
}
