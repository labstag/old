<?php

namespace Labstag\Handler;

use Labstag\Entity\Template;

class TemplatePublishingHandler
{
    public function handle(Template $entity): void
    {
        unset($entity);
        // your logic for publishing book or/and eg. return your custom data
    }
}
