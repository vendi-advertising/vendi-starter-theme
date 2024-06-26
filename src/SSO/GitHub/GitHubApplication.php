<?php

namespace Vendi\Theme\SSO\GitHub;

use Vendi\Theme\SSO\SsoApplicationBase;

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
