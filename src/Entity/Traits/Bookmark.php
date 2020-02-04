<?php

namespace Labstag\Entity\Traits;

use Doctrine\ORM\PersistentCollection;
use Labstag\Entity\Bookmark as BookmarkEntity;

trait Bookmark
{
    public function getBookmarks(): PersistentCollection
    {
        return $this->bookmarks;
    }

    public function addBookmark(BookmarkEntity $bookmark): self
    {
        if (!$this->bookmarks->contains($bookmark)) {
            $this->bookmarks[] = $bookmark;
        }

        return $this;
    }

    public function removeBookmark(BookmarkEntity $bookmark): self
    {
        if ($this->bookmarks->contains($bookmark)) {
            $this->bookmarks->removeElement($bookmark);
        }

        return $this;
    }
}
