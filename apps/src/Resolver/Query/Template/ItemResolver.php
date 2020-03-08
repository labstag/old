<?php

namespace Labstag\Resolver\Query\Template;

use ApiPlatform\Core\GraphQl\Resolver\QueryItemResolverInterface;
use Labstag\Entity\Template;
use Labstag\Repository\TemplateRepository;

final class ItemResolver implements QueryItemResolverInterface
{
    /**
     * @var TemplateRepository
     */
    private $repository;

    public function __construct(TemplateRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * @param Template|null $item
     *
     * @return Template
     */
    public function __invoke($item, array $context)
    {
        unset($context);

        // Query arguments are in $context['args'].

        // Do something with the book.
        // Or fetch the book if it has not been retrieved.

        return $item;
    }
}
