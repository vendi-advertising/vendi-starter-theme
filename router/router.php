<?php

use Laminas\Diactoros\Response\JsonResponse;
use Laminas\Diactoros\ServerRequestFactory;
use League\OAuth2\Client\Provider\Github;
use League\OAuth2\Client\Token\AccessToken;
use League\Route\Router;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use TheNetworg\OAuth2\Client\Provider\Azure;
use Vendi\Theme\SSO\Azure\AzureApplicationUtility;
use Vendi\Theme\SSO\GitHub\GitHubApplicationUtility;
use Vendi\Theme\SsoRouter;

$request = ServerRequestFactory::fromGlobals($_SERVER, $_GET, $_POST, $_COOKIE, $_FILES);

$router = new Router;

const VENDI_SESSION_KEY_EMAIL = 'vendi.sso.email';
const VENDI_SESSION_KEY_TARGET = 'vendi.sso.target';
const VENDI_SESSION_KEY_OAUTH2_STATE = 'OAuth2.state';

$router
    ->post(
        SsoRouter::VENDI_PATH_SSO_LOOKUP,
        static function (ServerRequestInterface $request): ResponseInterface {

            if (!$email = ($request->getParsedBody()['email'] ?? null)) {
                return new JsonResponse(['error' => 'Email address is required.'], 400);
            }

            if (!$ssoTarget = ($request->getParsedBody()['sso-target'] ?? null)) {
                return new JsonResponse(['error' => 'SSO target is required.'], 400);
            }

            $provider = match ($ssoTarget) {
                'azure' => (new AzureApplicationUtility)->getProviderForEmailAddress($email, $request),
                'github' => (new GitHubApplicationUtility)->getProviderForEmailAddress($email, $request),
                default => null,
            };

            if (!$provider) {
                return new JsonResponse(['error' => 'Unknown SSO target.'], 400);
            }

            $isProviderValid = match ($ssoTarget) {
                'azure' => $provider instanceof Azure,
                'github' => $provider instanceof Github,
                default => false,
            };

            if (!$isProviderValid) {
                return new JsonResponse(['error' => 'Invalid authentication provider for email address'], 400);
            }

            switch ($ssoTarget) {
                case 'azure':
                    $provider->defaultEndPointVersion = Azure::ENDPOINT_VERSION_2_0;
                    $baseGraphUri = $provider->getRootMicrosoftGraphUri(null);
                    $provider->scope = 'openid profile email offline_access '.$baseGraphUri.'/User.Read';
                    break;

                case 'github':
                    $provider->scope = 'user:email';
                    break;
            }

            $authorizationUrl = $provider->getAuthorizationUrl(['scope' => $provider->scope, 'login_hint' => $email]);

            $_SESSION[VENDI_SESSION_KEY_EMAIL] = $email;
            $_SESSION[VENDI_SESSION_KEY_TARGET] = $ssoTarget;
            $_SESSION[VENDI_SESSION_KEY_OAUTH2_STATE] = $provider->getState();

            return new JsonResponse(
                [
                    'authorizationUrl' => $authorizationUrl,
                ]
            );

        }
    );

$router
    ->get(
        SsoRouter::VENDI_PATH_SSO_CALLBACK,
        static function (ServerRequestInterface $request): ResponseInterface {
            if (!$email = $_SESSION[VENDI_SESSION_KEY_EMAIL] ?? null) {
                throw new RuntimeException('No email address found in session');
            }

            $provider = match ($_SESSION[VENDI_SESSION_KEY_TARGET] ?? null) {
                'azure' => (new AzureApplicationUtility())->getProviderForEmailAddress($email, $request),
                'github' => (new GitHubApplicationUtility())->getProviderForEmailAddress($email, $request),
                default => null,
            };

            if (!$provider) {
                throw new RuntimeException('Invalid authentication provider for email address');
            }

            if ($_SESSION[VENDI_SESSION_KEY_OAUTH2_STATE] !== $provider->getState()) {
                throw new RuntimeException('Invalid state returned from authentication provider');
            }

            $accessToken = $provider->getAccessToken('authorization_code', [
                'code' => $request->getQueryParams()['code'],
            ]);

            if (!$accessToken instanceof AccessToken) {
                throw new RuntimeException('Invalid token type returned from authentication provider');
            }

            $resourceOwner = $provider->getResourceOwner($accessToken);

            $identifiedEmail = $resourceOwner->toArray()['email'];

            if (!$user = get_user_by('email', $identifiedEmail)) {
                throw new RuntimeException('The supplied email address is not associated with a user account');
            }

            wp_set_auth_cookie($user->ID);

            unset($_SESSION[VENDI_SESSION_KEY_EMAIL], $_SESSION[VENDI_SESSION_KEY_TARGET], $_SESSION[VENDI_SESSION_KEY_OAUTH2_STATE]);

            return new Laminas\Diactoros\Response\RedirectResponse(admin_url());
        }
    );


$response = $router->dispatch($request);

// send the response to the browser
(new Laminas\HttpHandlerRunner\Emitter\SapiEmitter)->emit($response);
exit;
