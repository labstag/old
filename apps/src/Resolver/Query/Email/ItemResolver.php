<?php

namespace Labstag\Resolver\Query\Email;

use ApiPlatform\Core\GraphQl\Resolver\QueryItemResolverInterface;
use Labstag\Entity\Email;
use Labstag\Repository\EmailRepository;

final class ItemResolver implements QueryItemResolverInterface
{
    /**
     * @var EmailRepository
     */
    private $repository;

    public function __construct(EmailRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * @param Email|null $item
     *
     * @return Email
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
