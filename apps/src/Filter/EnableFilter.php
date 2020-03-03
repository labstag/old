<?php

namespace Labstag\Filter;

use Doctrine\ORM\Mapping\ClassMetadata;
use Doctrine\ORM\Query\Filter\SQLFilter;
use Doctrine\ORM\EntityManagerInterface;
use \ReflectionProperty;
use \RuntimeException;

class EnableFilter extends SQLFilter
{

    /**
     * @var EntityManagerInterface
     */
    protected $entityManager;

    public function addFilterConstraint(ClassMetadata $targetEntity, $targetTableAlias)
    {
        $class = $targetEntity->getName();

        // dump(get_class_vars($class));
        $methods = get_class_methods($class);
        if (!in_array('isEnable', $methods)) {
            return '';
        }

        return $targetTableAlias.'.enable IS TRUE';
    }
}
