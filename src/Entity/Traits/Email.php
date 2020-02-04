<?php

namespace Labstag\Entity\Traits;

use Doctrine\ORM\PersistentCollection;
use Labstag\Entity\Email as EmailEntity;

trait Email
{
    public function getEmails(): PersistentCollection
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
