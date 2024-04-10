<?php

namespace Vendi\Theme\SSO\GitHub;

use Vendi\Theme\SSO\SsoApplicationInterface;

class GitHubApplication implements SsoApplicationInterface
{
    /**
     * @var GitHubClientSecret[]
     */
    private array $clientSecrets = [];

    public function __construct(
        private readonly string $name,
        private readonly string $clientId,
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

    /**
     * @return GitHubClientSecret[]
     */
    public function getClientSecrets(): array
    {
        return $this->clientSecrets;
    }

    public function getEmailDomains(): array
    {
        return $this->emailDomains;
    }

    public function addClientSecret(GitHubClientSecret $clientSecret): void
    {
        $this->clientSecrets[] = $clientSecret;
    }
}
