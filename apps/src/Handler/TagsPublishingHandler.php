<?php

namespace Labstag\Handler;

use Labstag\Entity\Tags;

class TagsPublishingHandler
{
    public function handle(Tags $entity): void
    {
        unset($entity);
        // your logic for publishing book or/and eg. return your custom data
    }
}
