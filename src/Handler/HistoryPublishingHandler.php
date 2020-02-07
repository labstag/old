<?php

namespace Labstag\Handler;

use Labstag\Entity\History;

class HistoryPublishingHandler
{
    public function handle(History $entity): void
    {
        unset($entity);
        // your logic for publishing book or/and eg. return your custom data
    }
}
