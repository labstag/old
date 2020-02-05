<?php

namespace Labstag\Entity\Traits;

use Doctrine\ORM\PersistentCollection;
use Labstag\Entity\Phone as PhoneEntity;

trait Phone
{
    /**
     * Undocumented function
     *
     * @return mixed
     */
    public function getPhones()
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
