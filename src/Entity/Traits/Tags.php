<?php

namespace Labstag\Entity\Traits;

use Doctrine\ORM\PersistentCollection;
use Labstag\Entity\Tags as TagsEntity;

trait Tags
{
    /**
     * Undocumented function
     *
     * @return mixed
     */
    public function getTags()
    {
        return $this->tags;
    }

    public function addTag(TagsEntity $tag): self
    {
        if (!$this->tags->contains($tag)) {
            $this->tags[] = $tag;
        }

        return $this;
    }

    public function removeTag(TagsEntity $tag): self
    {
        if ($this->tags->contains($tag)) {
            $this->tags->removeElement($tag);
        }

        return $this;
    }
}
