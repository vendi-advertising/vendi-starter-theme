<?php

namespace Vendi\Theme\SSO\GitHub;

class GitHubApplication implements GitHubApplicationInterface
{
    /**
     * @var GitHubClientSecretInterface[]
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

    public function getClientSecrets(): array
    {
        return $this->clientSecrets;
    }

    public function getEmailDomains(): array
    {
        return $this->emailDomains;
    }

    public function addClientSecret(GitHubClientSecretInterface $clientSecret): void
    {
        $this->clientSecrets[] = $clientSecret;
    }
}
