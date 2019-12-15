<?php

namespace Labstag\Entity\Traits;

use Doctrine\Common\Collections\Collection;
use Labstag\Entity\Post as PostEntity;

trait Post
{
    /**
     * @return Collection|PostEntity
     */
    public function getPosts(): Collection
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
