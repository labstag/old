<?php

namespace Labstag\Handler;

use Labstag\Entity\Templates;

class TemplatesPublishingHandler
{
    public function handle(Templates $entity): void
    {
        unset($entity);
        // your logic for publishing book or/and eg. return your custom data
    }
}
