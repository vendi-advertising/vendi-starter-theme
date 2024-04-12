<?php

namespace Vendi\Theme\SSO\Google;

use Vendi\Theme\SSO\SsoApplicationBase;

final class GoogleApplication extends SsoApplicationBase
{
    /**
     * @var GoogleClientSecret[]
     */
    private array $clientSecrets = [];

    /**
     * @return GoogleClientSecret[]
     */
    public function getClientSecrets(): array
    {
        return $this->clientSecrets;
    }

    public function addClientSecret(GoogleClientSecret $clientSecret): void
    {
        $this->clientSecrets[] = $clientSecret;
    }
}
