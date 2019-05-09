<?php

namespace Labstag\Twig;

use DateTime;
use Twig\TwigFilter;
use Twig\TwigFunction;
use Twig\Extension\AbstractExtension;

class CrudExtension extends AbstractExtension
{
    public function getFilters(): array
    {
        return [
            // If your filter generates SAFE HTML, you should add a third
            // parameter: ['is_safe' => ['html']]
            // Reference: https://twig.symfony.com/doc/2.x/advanced.html#automatic-escaping
            new TwigFilter('crudExist', [$this, 'crudExist']),
        ];
    }

    public function getFunctions(): array
    {
        return [
            new TwigFunction('crudExist', [$this, 'crudExist']),
        ];
    }

    public function crudExist($entity, $key)
    {
        $return = '';
        $methods = get_class_methods($entity);
        foreach ($methods as $method) {
            if( strtolower($method) == strtolower("get".$key)) {
                $return = $entity->$method();
                if (is_object($return) && !($return instanceof DateTime)){
                    $return = $return->__toString();
                }
            }
        }

        return $return;
    }
}
