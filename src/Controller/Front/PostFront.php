<?php

namespace Labstag\Controller\Front;

use Labstag\Entity\Category;
use Labstag\Entity\Post;
use Labstag\Entity\Tags;
use Labstag\Entity\User;
use Labstag\Lib\ControllerLib;
use Labstag\Repository\PostRepository;
use Symfony\Component\Filesystem\Exception\FileNotFoundException;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/post")
 */
class PostFront extends ControllerLib
{
    /**
     * @Route("/user/{user}", name="posts_user")
     */
    public function postUser(User $user, PostRepository $repository): Response
    {
        $posts = $repository->findAllActiveByUser($user);
        $this->paginator($posts);

        return $this->twig('front/posts/list.html.twig');
    }

    /**
     * @Route("/category/{slug}", name="posts_category")
     */
    public function postCategory(Category $category, PostRepository $repository): Response
    {
        $posts = $repository->findAllActiveByCategory($category);
        $this->paginator($posts);

        return $this->twig('front/posts/list.html.twig');
    }

    /**
     * @Route("/tags/{slug}", name="posts_tag")
     */
    public function PostTags(Tags $tag, PostRepository $repository): Response
    {
        $posts = $repository->findAllActiveByTag($tag);
        $this->paginator($posts);

        return $this->twig('front/posts/list.html.twig');
    }

    /**
     * @Route("/{slug}", name="posts_show")
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
     * @Route("/", name="posts_list")
     */
    public function postList(PostRepository $repository): Response
    {
        $posts = $repository->findAllActive();
        $this->paginator($posts);

        return $this->twig('front/posts/list.html.twig');
    }
}
