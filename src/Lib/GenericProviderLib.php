<?php

namespace Labstag\Lib;

use League\OAuth2\Client\Provider\GenericProvider;

class GenericProviderLib extends GenericProvider
{

    protected $scopes;

    protected $scopeSeparator;
    /**
     * @inheritdoc
     */
    public function setDefaultScopes($scopes)
    {
        $this->scopes = $scopes;
    }

    public function getDefaultScopes()
    {
        return $this->scopes;
    }

    public function setScopeSeparator($scopeSeparator)
    {
        $this->scopeSeparator = $scopeSeparator;
    }

    public function getScopeSeparator()
    {
        return (!is_null($this->scopeSeparator)) ? $this->scopeSeparator : parent::getScopeSeparator();
    }
}