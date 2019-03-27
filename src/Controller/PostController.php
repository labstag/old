<?php

namespace App\Controller;

use App\Entity\Post;
use App\Entity\Tags;
use App\Entity\User;
use App\Entity\Category;
use App\Lib\AbstractControllerLib;
use App\Repository\PostRepository;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Filesystem\Exception\FileNotFoundException;

/**
 * @Route("/post")
 */
class PostController extends AbstractControllerLib
{
    private $postRepository;
    private $categoryRepository;

    public function __construct(ContainerInterface $container, PostRepository $postRepository)
    {
        parent::__construct($container);
        $this->postRepository = $postRepository;
    }

    /**
     * @Route("/user/{user}", name="posts_user")
     */
    public function user(User $user)
    {
        $posts = $this->postRepository->findAllActiveByUser($user);
        $pagination = $this->paginator($posts);
        return $this->twig(
            'posts/list.html.twig',
            array(
                'pagination'           => $pagination,
            )
        );
    }

    /**
     * @Route("/category/{slug}", name="posts_category")
     */
    public function category(Category $category)
    {
        $posts = $this->postRepository->findAllActiveByCategory($category);
        $pagination = $this->paginator($posts);
        return $this->twig(
            'posts/list.html.twig',
            array(
                'pagination'           => $pagination,
            )
        );
    }

    /**
     * @Route("/tags/{slug}", name="posts_tag")
     */
    public function tags(Tags $tag)
    {
        $posts = $this->postRepository->findAllActiveByTag($tag);

        $pagination = $this->paginator($posts);
        return $this->twig(
            'posts/list.html.twig',
            array(
                'pagination'           => $pagination,
            )
        );
    }

    /**
     * @Route("/{slug}", name="posts_show")
     */
    public function show(Post $post)
    {
        if (!$post->isEnable()) {
            throw new FileNotFoundException('The product does not exist');
        }

        return $this->twig(
            'posts/show.html.twig',
            array(
                'post' => $post,
            )
        );
    }

    /**
     * @Route("/", name="posts_list")
     */
    public function index()
    {
        $posts      = $this->postRepository->findAllActive();
        $pagination = $this->paginator($posts);
        return $this->twig(
            'posts/list.html.twig',
            array(
                'pagination'           => $pagination,
            )
        );
    }
}
