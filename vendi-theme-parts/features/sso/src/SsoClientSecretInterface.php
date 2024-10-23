<?php

namespace Vendi\Theme\Feature\SSO;

interface SsoClientSecretInterface
{
    public function getSecret(): string;
}
