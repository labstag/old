<?php

namespace Labstag\Handler;

use Labstag\Entity\Formbuilder;

class FormbuilderPublishingHandler
{
    public function handle(FormBuilder $entity): array
    {
        unset($entity);
        // your logic for publishing book or/and eg. return your custom data
    }
}
