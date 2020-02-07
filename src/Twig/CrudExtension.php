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
            new TwigFunction('pageDeletebreak', [$this, 'pageDeletebreak']),
        ];
    }

    public function pageDeletebreak(string $content): string
    {
        return str_replace('<p><!-- pagebreak --></p>', '', $content);
    }

    public function pagebreak(string $content): string
    {
        $contents = explode('<p><!-- pagebreak --></p>', $content);

        return $contents[0];
    }

    /**
     * @param mixed $entity
     */
    public function crudExist($entity, string $key): string
    {
        $return  = '';
        $methods = get_class_methods($entity);
        foreach ($methods as $method) {
            if (strtolower($method) == strtolower('get'.$key)) {
                /** @var mixed $return */
                $return = $entity->{$method}();
                if (is_object($return) && !($return instanceof DateTime)) {
                    $methods = get_class_methods($return);
                    if (in_array('__toString', $methods)) {
                        $return = $return->__toString();

                        continue;
                    }

                    $return = '';
                }
            }
        }

        return $return;
    }
}
