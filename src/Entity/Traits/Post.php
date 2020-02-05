<?php

namespace Labstag\Entity\Traits;

use Doctrine\ORM\PersistentCollection;
use Labstag\Entity\Post as PostEntity;

trait Post
{
    /**
     * Undocumented function
     *
     * @return mixed
     */
    public function getPosts()
    {
        return $this->posts;
    }

    public function addPost(PostEntity $post): self
    {
        if (!$this->posts->contains($post)) {
            $this->posts[] = $post;
        }

        return $this;
    }

    public function removePost(PostEntity $post): self
    {
        if ($this->posts->contains($post)) {
            $this->posts->removeElement($post);
        }

        return $this;
    }
}
