<?php

namespace Vendi\Theme\Feature\SSO;

abstract class SsoApplicationBase implements SsoApplicationInterface
{
    public function __construct(
        private readonly string $name,
        private readonly string $clientId,
        private readonly array $emailDomains
    ) {
    }

    final public function getName(): string
    {
        return $this->name;
    }

    final public function getClientId(): string
    {
        return $this->clientId;
    }

    final public function getEmailDomains(): array
    {
        return $this->emailDomains;
    }
}
