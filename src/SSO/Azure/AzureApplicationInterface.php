<?php

namespace Vendi\Theme\SSO\Azure;

use Vendi\Theme\SSO\SsoApplicationInterface;

interface AzureApplicationInterface extends SsoApplicationInterface
{
    public function getTenantId(): string;

    /**
     * @return AzureClientSecretInterface[]
     */
    public function getClientSecrets(): array;
}
