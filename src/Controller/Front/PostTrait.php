<?php

namespace Labstag\Controller\Front;

use Labstag\Entity\Category;
use Labstag\Entity\Post;
use Labstag\Entity\Tags;
use Labstag\Entity\User;
use Labstag\Repository\PostRepository;
use Symfony\Component\Filesystem\Exception\FileNotFoundException;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

trait PostTrait
{
    /**
     * @Route("/post/user/{user}", name="posts_user")
     */
    public function postUser(
        User $user,
        PostRepository $postRepository
    ): Response
    {
        $posts = $postRepository->findAllActiveByUser($user);
        $this->paginator($posts);

        return $this->twig('front/posts/list.html.twig');
    }

    /**
     * @Route("/post/category/{slug}", name="posts_category")
     */
    public function postCategory(
        Category $category,
        PostRepository $postRepository
    ): Response
    {
        $posts = $postRepository->findAllActiveByCategory($category);
        $this->paginator($posts);

        return $this->twig('front/posts/list.html.twig');
    }

    /**
     * @Route("/post/tags/{slug}", name="posts_tag")
     */
    public function PostTags(
        Tags $tag,
        PostRepository $postRepository
    ): Response
    {
        $posts = $postRepository->findAllActiveByTag($tag);
        $this->paginator($posts);

        return $this->twig('front/posts/list.html.twig');
    }

    /**
     * @Route("/post/{slug}", name="posts_show")
     */
    public function postShow(Post $post): Response
    {
        if (!$post->isEnable()) {
            throw new FileNotFoundException('The product does not exist');
        }

        return $this->twig(
            'front/posts/show.html.twig',
            ['post' => $post]
        );
    }

    /**
     * @Route("/post/", name="posts_list")
     */
    public function postList(PostRepository $postRepository): Response
    {
        $posts = $postRepository->findAllActive();
        $this->paginator($posts);

        return $this->twig('front/posts/list.html.twig');
    }
}
