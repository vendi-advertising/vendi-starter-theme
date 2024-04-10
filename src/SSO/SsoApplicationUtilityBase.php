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

    final public function getAllRegisteredProviders(): array
    {
        if (!$ssoProviders = get_field('sso_providers', 'option')) {
            return [];
        }

        return $ssoProviders;
    }

    final public function getAllRegisteredProvidersForType(string $type): array
    {
        return array_filter(
            $this->getAllRegisteredProviders(),
            static function ($provider) use ($type) {
                return $type === $provider['acf_fc_layout'];
            }
        );
    }
}
