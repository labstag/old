<?php

namespace spec\Labstag\Twig;

use Labstag\Twig\CrudExtension;
use PhpSpec\ObjectBehavior;

class CrudExtensionSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType(CrudExtension::class);
    }
}
