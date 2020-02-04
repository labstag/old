<?php

namespace Labstag\Entity\Traits;

use Doctrine\Common\Collections\ArrayCollection;
use Labstag\Entity\Post as PostEntity;

trait Post
{
    public function getPosts(): ArrayCollection
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
