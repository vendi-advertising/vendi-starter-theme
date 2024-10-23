<?php

namespace Vendi\Theme\Feature\SSO;

interface SsoApplicationInterface
{
    public function getName(): string;

    public function getClientId(): string;

    public function getClientSecrets(): array;

    public function getEmailDomains(): array;
}
