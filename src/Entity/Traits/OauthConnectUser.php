<?php

namespace Labstag\Entity\Traits;

use Doctrine\Common\Collections\ArrayCollection;
use Labstag\Entity\OauthConnectUser as OauthConnectUserEntity;

trait OauthConnectUser
{
    public function getOauthConnectUsers(): ArrayCollection
    {
        return $this->oauthConnectUsers;
    }

    public function addOauthConnectUser(OauthConnectUserEntity $oauthConnectUser): self
    {
        if (!$this->oauthConnectUsers->contains($oauthConnectUser)) {
            $this->oauthConnectUsers[] = $oauthConnectUser;
        }

        return $this;
    }

    public function removeOauthConnectUser(OauthConnectUserEntity $oauthConnectUser): self
    {
        if ($this->oauthConnectUsers->contains($oauthConnectUser)) {
            $this->oauthConnectUsers->removeElement($oauthConnectUser);
        }

        return $this;
    }
}
