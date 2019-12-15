<?php

namespace Labstag\Entity\Traits;

use Doctrine\Common\Collections\Collection;
use Labstag\Entity\Email as EmailEntity;

trait Email
{
    /**
     * @return Collection|EmailEntity
     */
    public function getEmails(): Collection
    {
        return $this->emails;
    }

    public function addEmail(EmailEntity $email): self
    {
        if (!$this->emails->contains($email)) {
            $this->emails[] = $email;
        }

        return $this;
    }

    public function removeEmail(EmailEntity $email): self
    {
        if ($this->emails->contains($email)) {
            $this->emails->removeElement($email);
        }

        return $this;
    }
}
