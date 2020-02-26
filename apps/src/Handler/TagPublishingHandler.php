<?php

namespace Labstag\Handler;

use Labstag\Entity\Tag;

class TagPublishingHandler
{
    public function handle(Tag $entity): void
    {
        unset($entity);
        // your logic for publishing book or/and eg. return your custom data
    }
}
