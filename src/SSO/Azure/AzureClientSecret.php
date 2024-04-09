<?php

namespace Vendi\Theme\SSO\Azure;

use DateTimeImmutable;

class AzureClientSecret implements AzureClientSecretInterface
{
    public function __construct(
        private readonly DateTimeImmutable $startDate,
        private readonly DateTimeImmutable $expirationDate,
        private readonly string $secret,
        private readonly ?string $secretId,
    ) {
    }

    public function getStartDate(): DateTimeImmutable
    {
        return $this->startDate;
    }

    public function getExpirationDate(): DateTimeImmutable
    {
        return $this->expirationDate;
    }

    public function getSecret(): string
    {
        return $this->secret;
    }

    public function getSecretId(): ?string
    {
        return $this->secretId;
    }
}
