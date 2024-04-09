<?php

use Laminas\Diactoros\Response\JsonResponse;
use Laminas\Diactoros\ServerRequestFactory;
use League\OAuth2\Client\Token\AccessToken;
use League\Route\Router;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use TheNetworg\OAuth2\Client\Provider\Azure;
use Vendi\Theme\SSO\Azure\AzureApplicationUtility;
use Vendi\Theme\SsoRouter;

$request = ServerRequestFactory::fromGlobals($_SERVER, $_GET, $_POST, $_COOKIE, $_FILES);

$router = new Router;

$router
    ->post(
        SsoRouter::VENDI_PATH_SSO_LOOKUP,
        static function (ServerRequestInterface $request): ResponseInterface {

            if (!$email = ($request->getParsedBody()['email'] ?? null)) {
                return new JsonResponse(['error' => 'Email address is required.'], 400);
            }


            if (!$provider = (new AzureApplicationUtility)->getProviderForEmailAddress($email, $request)) {
                return new JsonResponse(['error' => 'No provider found for email address'], 400);
            }

            if (!$provider instanceof Azure) {
                return new JsonResponse(['error' => 'Invalid authentication provider for email address'], 400);
            }

            $provider->defaultEndPointVersion = Azure::ENDPOINT_VERSION_2_0;
            $baseGraphUri = $provider->getRootMicrosoftGraphUri(null);
            $provider->scope = 'openid profile email offline_access '.$baseGraphUri.'/User.Read';

            $authorizationUrl = $provider->getAuthorizationUrl(['scope' => $provider->scope, 'login_hint' => $email]);

            $_SESSION['vendi.sso.email'] = $email;
            $_SESSION['OAuth2.state'] = $provider->getState();

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
            if (!$email = $_SESSION['vendi.sso.email'] ?? null) {
                throw new RuntimeException('No email address found in session');
            }

            if (!$provider = (new AzureApplicationUtility())->getProviderForEmailAddress($email, $request)) {
                throw new RuntimeException('Could not find authentication provider for email address');
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

            return new Laminas\Diactoros\Response\RedirectResponse(admin_url());
        }
    );


$response = $router->dispatch($request);

// send the response to the browser
(new Laminas\HttpHandlerRunner\Emitter\SapiEmitter)->emit($response);
exit;
