<?php

namespace Vendi\Theme\SSO\GitHub;

use DateTimeImmutable;
use League\OAuth2\Client\Provider\AbstractProvider;
use League\OAuth2\Client\Provider\Github;
use League\Uri\UriString;
use Psr\Http\Message\ServerRequestInterface;
use Vendi\Theme\SSO\SsoApplicationUtilityBase;
use Vendi\Theme\SsoRouter;

class GitHubApplicationUtility extends SsoApplicationUtilityBase
{
    public function getProviderForEmailAddress(string $emailAddress, ServerRequestInterface $request): ?AbstractProvider
    {
        if (!$application = $this->getApplicationForEmailAddress($emailAddress)) {
            return null;
        }

        $maybeClientSecrets = [];
        foreach ($application->getClientSecrets() as $client_secret) {
            $maybeClientSecrets[] = $client_secret;
            break;
        }

        if (!count($maybeClientSecrets)) {
            return null;
        }
        $thisClientSecret = end($maybeClientSecrets);

        $redirectUrl = UriString::build(
            [
                'scheme' => 'https',
                'host' => $request->getUri()->getHost(),
                'path' => SsoRouter::VENDI_PATH_SSO_CALLBACK,
            ]
        );

        return new Github(
            [
                'clientId' => $application->getClientId(),
                'clientSecret' => $thisClientSecret->getSecret(),
                'redirectUri' => $redirectUrl,
                'scopes' => ['user:email'],
            ],
        );
    }

    protected function getAllApplications(): array
    {
        if (!$ssoProviders = get_field('sso_providers', 'option')) {
            return [];
        }

        $applications = [];

        foreach ($ssoProviders as $provider) {

            if ('github_provider' !== $provider['acf_fc_layout']) {
                continue;
            }

            $app = new GitHubApplication(
                $provider['application_name'],
                $provider['client_id'],
                explode("\n", mb_strtolower($provider['domains']))
            );

            if (!array_key_exists('secrets', $provider) || !is_array($provider['secrets']) || !count($provider['secrets'])) {
                continue;
            }

            foreach ($provider['secrets'] as $secret) {
                $app->addClientSecret(
                    new GitHubClientSecret(
                        $secret['client_secret'],
                    )
                );
            }

            $applications[] = $app;
        }

        return $applications;
    }
}
