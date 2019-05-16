<?php

namespace Labstag\Twig;

use DateTime;
use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;
use Twig\TwigFunction;

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
            new TwigFunction('pagebreak', [$this, 'pagebreak']),
        ];
    }

    public function pagebreak($content)
    {
        $contents = explode("<p><!-- pagebreak --></p>", $content);

        return $contents[0];
    }

    public function crudExist($entity, $key)
    {
        $return  = '';
        $methods = get_class_methods($entity);
        foreach ($methods as $method) {
            if (strtolower($method) == strtolower('get'.$key)) {
                $return = $entity->{$method}();
                if (is_object($return) && !($return instanceof DateTime)) {
                    $return = $return->__toString();
                }
            }
        }

        return $return;
    }
}
