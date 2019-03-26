<?php

namespace App\Controller;

use App\Entity\Category;
use App\Entity\Post;
use App\Entity\Tags;
use App\Lib\AbstractControllerLib;
use App\Repository\PostRepository;
use Symfony\Component\Filesystem\Exception\FileNotFoundException;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/post")
 */
class PostController extends AbstractControllerLib
{
    private $postRepository;
    private $categoryRepository;

    public function __construct(PostRepository $postRepository)
    {
        $this->postRepository = $postRepository;
    }

    /**
     * @Route("/category/{slug}", name="posts_category")
     */
    public function category(Category $category)
    {
        $posts = $this->postRepository->findAllActiveByCategory($category);

        return $this->twig(
            'posts/list.html.twig',
            array(
                'posts'           => $posts,
                'controller_name' => 'BlogController',
            )
        );
    }

    /**
     * @Route("/tags/{slug}", name="posts_tag")
     */
    public function tags(Tags $tag)
    {
        $posts = $this->postRepository->findAllActiveByTag($tag);

        return $this->twig(
            'posts/list.html.twig',
            array(
                'posts'           => $posts,
                'controller_name' => 'BlogController',
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
        $posts = $this->postRepository->findAllActive();

        return $this->twig(
            'posts/list.html.twig',
            array(
                'posts'           => $posts,
                'controller_name' => 'BlogController',
            )
        );
    }
}
