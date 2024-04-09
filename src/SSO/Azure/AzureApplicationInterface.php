<?php

namespace Vendi\Theme\SSO\Azure;

interface AzureApplicationInterface
{
    public function getName(): string;

    public function getClientId(): string;

    public function getTenantId(): string;

    /**
     * @return AzureClientSecretInterface[]
     */
    public function getClientSecrets(): array;

    public function getEmailDomains(): array;
}
