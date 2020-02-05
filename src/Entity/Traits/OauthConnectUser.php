<?php

namespace Labstag\Entity\Traits;

use Labstag\Entity\OauthConnectUser as OauthConnectUserEntity;

trait OauthConnectUser
{
    /**
     * @return mixed
     */
    public function getOauthConnectUsers()
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
