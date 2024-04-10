<?php

namespace Vendi\Theme\SSO\Azure;

use DateTimeImmutable;
use Vendi\Theme\SSO\SsoClientSecretBase;

class AzureClientSecret extends SsoClientSecretBase
{
    public function __construct(
        private readonly DateTimeImmutable $startDate,
        private readonly DateTimeImmutable $expirationDate,
        string $secret,
        private readonly ?string $secretId,
    ) {
        parent::__construct($secret);
    }

    public function getStartDate(): DateTimeImmutable
    {
        return $this->startDate;
    }

    public function getExpirationDate(): DateTimeImmutable
    {
        return $this->expirationDate;
    }

    public function getSecretId(): ?string
    {
        return $this->secretId;
    }
}
