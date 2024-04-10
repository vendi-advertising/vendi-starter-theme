<?php

namespace Vendi\Theme\SSO;

interface SsoClientSecretInterface
{
    public function getSecret(): string;
}
