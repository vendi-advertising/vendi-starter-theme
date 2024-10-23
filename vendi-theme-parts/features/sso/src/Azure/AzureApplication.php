<?php

namespace Vendi\Theme\Feature\SSO\Azure;

use Vendi\Theme\Feature\SSO\SsoApplicationBase;

class AzureApplication extends SsoApplicationBase
{
    /**
     * @var AzureClientSecret[]
     */
    private array $clientSecrets = [];

    public function __construct(
        string $name,
        string $clientId,
        private readonly string $tenantId,
        array $emailDomains
    ) {
        parent::__construct($name, $clientId, $emailDomains);
    }

    public function getTenantId(): string
    {
        return $this->tenantId;
    }

    /**
     * @return AzureClientSecret[]
     */
    public function getClientSecrets(): array
    {
        return $this->clientSecrets;
    }

    public function addClientSecret(AzureClientSecret $clientSecret): void
    {
        $this->clientSecrets[] = $clientSecret;
    }
}
