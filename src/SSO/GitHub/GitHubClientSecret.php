<?php

namespace Vendi\Theme\SSO\GitHub;

class GitHubClientSecret implements GitHubClientSecretInterface
{
    public function __construct(
        private readonly string $secret,
    ) {
    }

    public function getSecret(): string
    {
        return $this->secret;
    }
}
