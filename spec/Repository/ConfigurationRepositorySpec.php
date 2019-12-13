<?php

namespace spec\Labstag\Repository;

use Labstag\Repository\ConfigurationRepository;
use PhpSpec\ObjectBehavior;

class ConfigurationRepositorySpec extends ObjectBehavior
{
    public function it_is_initializable()
    {
        $this->shouldHaveType(ConfigurationRepository::class);
    }
}
