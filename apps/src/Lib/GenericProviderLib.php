<?php

namespace Labstag\Lib;

use League\OAuth2\Client\Provider\GenericProvider;

class GenericProviderLib extends GenericProvider
{

    /**
     * @var array|null
     */
    protected $scopes;

    /**
     * @var string
     */
    protected $scopeSeparator;

    /**
     * {@inheritdoc}
     */
    public function setDefaultScopes(array $scopes): void
    {
        $this->scopes = $scopes;
    }

    /**
     * @return array|null
     */
    public function getDefaultScopes()
    {
        return $this->scopes;
    }

    public function setScopeSeparator(string $scopeSeparator): void
    {
        $this->scopeSeparator = $scopeSeparator;
    }

    public function getScopeSeparator(): string
    {
        return (!empty($this->scopeSeparator)) ? $this->scopeSeparator : parent::getScopeSeparator();
    }
}
