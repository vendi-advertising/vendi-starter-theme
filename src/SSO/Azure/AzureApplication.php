<?php

namespace Vendi\Theme\SSO\Azure;


class AzureApplication implements AzureApplicationInterface
{
    /**
     * @var AzureClientSecretInterface[]
     */
    private array $clientSecrets = [];

    public function __construct(
        private readonly string $name,
        private readonly string $clientId,
        private readonly string $tenantId,
        private readonly array $emailDomains
    ) {
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getClientId(): string
    {
        return $this->clientId;
    }

    public function getTenantId(): string
    {
        return $this->tenantId;
    }

    /**
     * @return AzureClientSecretInterface[]
     */
    public function getClientSecrets(): array
    {
        return $this->clientSecrets;
    }

    public function getEmailDomains(): array
    {
        return $this->emailDomains;
    }

    public function addClientSecret(AzureClientSecretInterface $clientSecret): void
    {
        $this->clientSecrets[] = $clientSecret;
    }
}