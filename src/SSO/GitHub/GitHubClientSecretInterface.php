<?php

namespace Vendi\Theme\SSO\GitHub;

use Vendi\Theme\SSO\Azure\AzureClientSecretInterface;

interface GitHubClientSecretInterface
{
    public function getSecret(): string;
}
