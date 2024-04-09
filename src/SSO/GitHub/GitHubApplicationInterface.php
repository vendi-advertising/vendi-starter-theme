<?php

namespace Vendi\Theme\SSO\GitHub;

use Vendi\Theme\SSO\SsoApplicationInterface;

interface GitHubApplicationInterface extends SsoApplicationInterface
{
    /**
     * @return GitHubClientSecretInterface[]
     */
    public function getClientSecrets(): array;
}
