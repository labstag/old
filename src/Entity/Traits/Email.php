<?php

namespace Labstag\Entity\Traits;

use Doctrine\Common\Collections\ArrayCollection;
use Labstag\Entity\Email as EmailEntity;

trait Email
{
    public function getEmails(): ArrayCollection
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
