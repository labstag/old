<?php

namespace Labstag\Entity\Traits;

use Doctrine\Common\Collections\Collection;
use Labstag\Entity\Chapitre as ChapitreEntity;

trait Chapitre
{
    /**
     * @return ChapitreEntity|Collection
     */
    public function getChapitres(): Collection
    {
        return $this->chapitres;
    }

    public function getChapitresEnabled()
    {
        $chapitres = [];
        foreach ($this->chapitres as $chapitre) {
            if ($chapitre->isEnable()) {
                array_push($chapitres, $chapitre);
            }
        }

        return $chapitres;
    }

    public function addChapitre(ChapitreEntity $chapitre): self
    {
        if (!$this->chapitres->contains($chapitre)) {
            $this->chapitres[] = $chapitre;
        }

        return $this;
    }

    public function removeChapitre(ChapitreEntity $chapitre): self
    {
        if ($this->chapitres->contains($chapitre)) {
            $this->chapitres->removeElement($chapitre);
        }

        return $this;
    }
}
