<?php

namespace Labstag\Entity\Traits;

use Doctrine\Common\Collections\ArrayCollection;
use Labstag\Entity\History as HistoryEntity;

trait History
{
    public function getHistories(): ArrayCollection
    {
        return $this->histories;
    }

    public function addHistory(HistoryEntity $history): self
    {
        if (!$this->histories->contains($history)) {
            $this->histories[] = $history;
        }

        return $this;
    }

    public function removeHistory(HistoryEntity $history): self
    {
        if ($this->histories->contains($history)) {
            $this->histories->removeElement($history);
        }

        return $this;
    }
}
