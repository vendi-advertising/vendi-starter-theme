<?php

namespace Vendi\Theme\SSO\Azure;

use DateTimeImmutable;

interface AzureClientSecretInterface
{
    public function getStartDate(): DateTimeImmutable;

    public function getExpirationDate(): DateTimeImmutable;

    public function getSecret(): string;

    public function getSecretId(): ?string;
}
