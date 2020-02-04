<?php

namespace Labstag\Entity\Traits;

use Doctrine\ORM\PersistentCollection;
use Labstag\Entity\Chapitre as ChapitreEntity;

trait Chapitre
{
    public function getChapitres(): PersistentCollection
    {
        return $this->chapitres;
    }

    public function getChapitresEnabled(): array
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
