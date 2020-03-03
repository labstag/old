<?php

namespace Labstag\Filter;

use Doctrine\ORM\Mapping\ClassMetadata;
use Doctrine\ORM\Query\Filter\SQLFilter;

class EnableFilter extends SQLFilter
{
    public function addFilterConstraint(ClassMetadata $targetEntity, $targetTableAlias)
    {
        unset($targetEntity);

        return $targetTableAlias.'.enable IS TRUE';
    }
}
