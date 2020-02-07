<?php

namespace Labstag\Handler;

use Labstag\Entity\Chapitre;

class ChapitrePublishingHandler
{
    public function handle(Chapitre $entity): void
    {
        unset($entity);
        // your logic for publishing book or/and eg. return your custom data
    }
}
