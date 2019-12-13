<?php

namespace spec\Labstag\Twig;

use Labstag\Twig\CrudExtension;
use PhpSpec\ObjectBehavior;

class CrudExtensionSpec extends ObjectBehavior
{
    public function it_is_initializable()
    {
        $this->shouldHaveType(CrudExtension::class);
    }
}
