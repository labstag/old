<?php
namespace Labstag\Filter;

use Doctrine\ORM\Mapping\ClassMetadata;
use Gedmo\SoftDeleteable\Traits\SoftDeleteableEntity;
use Doctrine\ORM\Query\Filter\SQLFilter;

class TrashFilter extends SQLFilter
{
    public function addFilterConstraint(ClassMetadata $targetEntity, $targetTableAlias)
    {
        return $targetTableAlias.'.deleted_at IS NOT NULL';
    }
}
