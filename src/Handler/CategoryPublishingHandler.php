<?php

namespace Labstag\Handler;

use Labstag\Entity\Category;

class CategoryPublishingHandler
{
    public function handle(Category $entity): void
    {
        unset($entity);
        // your logic for publishing book or/and eg. return your custom data
    }
}
