<?php

namespace Vendi\Theme\Feature\SSO\Azure;

use DateTimeImmutable;
use Exception;
use League\OAuth2\Client\Provider\AbstractProvider;
use League\Uri\UriString;
use Psr\Http\Message\ServerRequestInterface;
use TheNetworg\OAuth2\Client\Provider\Azure;
use Vendi\Theme\Feature\SSO\SsoApplicationUtilityBase;
use Vendi\Theme\Feature\SSO\SsoRouter;

class AzureApplicationUtility extends SsoApplicationUtilityBase
{
    public function getProviderForEmailAddress(string $emailAddress, ServerRequestInterface $request): ?AbstractProvider
    {
        /**
         * This code is currently only built for Azure, however it should be
         * easily extendable to add other providers.
         *
         * See https://oauth2-client.thephpleague.com/
         */
        if (!$application = $this->getApplicationForEmailAddress($emailAddress)) {
            return null;
        }

        $maybeClientSecrets = [];
        foreach ($application->getClientSecrets() as $client_secret) {
            if ($client_secret->getExpirationDate() > new DateTimeImmutable()) {
                $maybeClientSecrets[$client_secret->getStartDate()->format('Y-m-d')] = $client_secret;
            }
        }

        if (!count($maybeClientSecrets)) {
            return null;
        }

        // "Youngest" at the end
        if (count($maybeClientSecrets) > 1) {
            ksort($maybeClientSecrets);
        }

        $thisClientSecret = end($maybeClientSecrets);

        $redirectUrl = UriString::build(
            [
                'scheme' => 'https',
                'host' => $request->getUri()->getHost(),
                'path' => SsoRouter::VENDI_PATH_SSO_CALLBACK,
            ]
        );

        return new Azure(
            [
                'clientId' => $application->getClientId(),
                'clientSecret' => $thisClientSecret->getSecret(),
                'redirectUri' => $redirectUrl,
                'tenant' => $application->getTenantId(),
                'scopes' => ['openid'],
                'defaultEndPointVersion' => '2.0',
            ],
        );
    }

    /**
     * @throws Exception
     */
    protected function getAllApplications(): array
    {
        $applicationFromAcf = $this->getAllRegisteredProvidersForType('azure_provider');

        $applications = [];

        foreach ($applicationFromAcf as $provider) {

            if (!array_key_exists('secrets', $provider) || !is_array($provider['secrets']) || !count($provider['secrets'])) {
                continue;
            }

            $app = new AzureApplication(
                $provider['application_name'],
                $provider['client_id'],
                $provider['tenant_id'],
                explode("\n", mb_strtolower($provider['domains']))
            );

            foreach ($provider['secrets'] as $secret) {
                $app->addClientSecret(
                    new AzureClientSecret(
                        new \DateTimeImmutable($secret['start_date']),
                        new \DateTimeImmutable($secret['expiration_date']),
                        $secret['client_secret'],
                        $secret['secret_id'],
                    )
                );
            }

            $applications[] = $app;
        }

        return $applications;
    }
}
