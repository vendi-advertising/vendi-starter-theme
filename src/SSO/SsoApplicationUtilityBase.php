<?php

namespace Vendi\Theme\SSO;

abstract class SsoApplicationUtilityBase
{
    /**
     * @return SsoApplicationInterface[]
     */
    abstract protected function getAllApplications(): array;

    final public function getApplicationForEmailAddress(string $emailAddress): ?SsoApplicationInterface
    {
        $emailParts = explode('@', mb_strtolower($emailAddress));
        [, $domain] = $emailParts;

        foreach ($this->getAllApplications() as $application) {
            if (in_array($domain, $application->getEmailDomains(), true)) {
                return $application;
            }
        }

        return null;
    }
}
