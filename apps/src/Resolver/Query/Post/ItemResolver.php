<?php

namespace Labstag\Resolver\Query\Post;

use ApiPlatform\Core\GraphQl\Resolver\QueryItemResolverInterface;
use Labstag\Entity\Post;
use Labstag\Repository\PostRepository;

final class ItemResolver implements QueryItemResolverInterface
{

    /**
     * @var PostRepository
     */
    private $repository;

    public function __construct(PostRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * @param Post|null $item
     *
     * @return Post
     */
    public function __invoke($item, array $context)
    {
        dump($context);
        $query = $this->repository->findAllActive($context);
        $query->setMaxResults(1);
        $item = $query->getQuery()->getOneOrNullResult();
        unset($context);

        // Query arguments are in $context['args'].

        // Do something with the book.
        // Or fetch the book if it has not been retrieved.

        return $item;
    }
}
