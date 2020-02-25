<?php

namespace Labstag\Twig;

use DateTime;
use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;
use Twig\TwigFunction;

class GraphqlExtension extends AbstractExtension
{
    public function getFilters(): array
    {
        return [
            // If your filter generates SAFE HTML, you should add a third
            // parameter: ['is_safe' => ['html']]
            // Reference: https://twig.symfony.com/doc/2.x/advanced.html#automatic-escaping
            new TwigFilter('graphqlQuery', [$this, 'graphqlQuery']),
        ];
    }

    public function getFunctions(): array
    {
        return [
            new TwigFunction('graphqlQuery', [$this, 'graphqlQuery']),
        ];
    }

    public function graphqlQuery(array $params): string
    {
        dump($params);
        return 'graphql_query';
    }
}
