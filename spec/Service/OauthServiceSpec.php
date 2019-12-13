<?php

namespace spec\Labstag\Service;

use Labstag\Service\OauthService;
use PhpSpec\ObjectBehavior;

class OauthServiceSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType(OauthService::class);
    }
}
