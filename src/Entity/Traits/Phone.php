<?php

namespace Labstag\Entity\Traits;

use Doctrine\Common\Collections\Collection;
use Labstag\Entity\Phone as PhoneEntity;

trait Phone
{
    /**
     * @return Collection|PhoneEntity[]
     */
    public function getPhones(): Collection
    {
        return $this->phones;
    }

    public function addPhone(PhoneEntity $phone): self
    {
        if (!$this->phones->contains($phone)) {
            $this->phones[] = $phone;
        }

        return $this;
    }

    public function removePhone(PhoneEntity $phone): self
    {
        if ($this->phones->contains($phone)) {
            $this->phones->removeElement($phone);
        }

        return $this;
    }
}
