<?php

namespace Vendi\Theme\Feature\SSO;

abstract class SsoClientSecretBase implements SsoClientSecretInterface
{
    public function __construct(
        private readonly string $secret,
    ) {
    }

    final public function getSecret(): string
    {
        return $this->secret;
    }
}
