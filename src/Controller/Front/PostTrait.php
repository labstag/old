<?php

namespace App\Controller\Front;

use App\Entity\Post;
use App\Entity\Tags;
use App\Entity\User;
use App\Entity\Category;
use App\Repository\PostRepository;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Filesystem\Exception\FileNotFoundException;

trait PostTrait
{

    /**
     * @Route("/post/user/{user}", name="posts_user")
     */
    public function postUser(User $user, PostRepository $postRepository)
    {
        $posts = $postRepository->findAllActiveByUser($user);
        $this->paginator($posts);
        return $this->render('front/posts/list.html.twig');
    }

    /**
     * @Route("/post/category/{slug}", name="posts_category")
     */
    public function postCategory(Category $category, PostRepository $postRepository)
    {
        $posts = $postRepository->findAllActiveByCategory($category);
        $this->paginator($posts);
        return $this->render('front/posts/list.html.twig');
    }

    /**
     * @Route("/post/tags/{slug}", name="posts_tag")
     */
    public function PostTags(Tags $tag, PostRepository $postRepository)
    {
        $posts = $postRepository->findAllActiveByTag($tag);
        $this->paginator($posts);
        return $this->render('front/posts/list.html.twig');
    }

    /**
     * @Route("/post/{slug}", name="posts_show")
     */
    public function postShow(Post $post)
    {
        if (!$post->isEnable()) {
            throw new FileNotFoundException('The product does not exist');
        }

        return $this->render(
            'front/posts/show.html.twig',
            array(
                'post' => $post,
            )
        );
    }

    /**
     * @Route("/post/", name="posts_list")
     */
    public function postList(PostRepository $postRepository)
    {
        $posts      = $postRepository->findAllActive();
        $this->paginator($posts);
        return $this->render('front/posts/list.html.twig');
    }
}
