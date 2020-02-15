<?php

namespace Labstag\Handler;

use Labstag\Entity\Bookmark;

class BookmarkPublishingHandler
{
    public function handle(Bookmark $entity): void
    {
        unset($entity);
        // your logic for publishing book or/and eg. return your custom data
    }
}
