<?php

namespace Vendi\Theme\Feature\SSO\GitHub;

use League\OAuth2\Client\Provider\AbstractProvider;
use League\OAuth2\Client\Provider\Github;
use League\Uri\UriString;
use Psr\Http\Message\ServerRequestInterface;
use Vendi\Theme\Feature\SSO\SsoApplicationUtilityBase;
use Vendi\Theme\Feature\SSO\SsoRouter;

class GitHubApplicationUtility extends SsoApplicationUtilityBase
{
    public function getProviderForEmailAddress(string $emailAddress, ServerRequestInterface $request): ?AbstractProvider
    {
        if (!$application = $this->getApplicationForEmailAddress($emailAddress)) {
            return null;
        }

        $allSecrets = $application->getClientSecrets();

        $thisClientSecret = reset($allSecrets);

        if (!$thisClientSecret) {
            return null;
        }

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
        $applicationFromAcf = $this->getAllRegisteredProvidersForType('github_provider');

        $applications = [];

        foreach ($applicationFromAcf as $provider) {

            if (!array_key_exists('secrets', $provider) || !is_array($provider['secrets']) || !count($provider['secrets'])) {
                continue;
            }

            $app = new GitHubApplication(
                $provider['application_name'],
                $provider['client_id'],
                explode("\n", mb_strtolower($provider['domains']))
            );

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
