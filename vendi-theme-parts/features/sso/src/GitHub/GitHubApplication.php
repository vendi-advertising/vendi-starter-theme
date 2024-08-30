<?php

namespace Vendi\Theme\Feature\SSO\GitHub;

use Vendi\Theme\Feature\SSO\SsoApplicationBase;

class GitHubApplication extends SsoApplicationBase
{
    /**
     * @var GitHubClientSecret[]
     */
    private array $clientSecrets = [];

    /**
     * @return GitHubClientSecret[]
     */
    public function getClientSecrets(): array
    {
        return $this->clientSecrets;
    }

    public function addClientSecret(GitHubClientSecret $clientSecret): void
    {
        $this->clientSecrets[] = $clientSecret;
    }
}
