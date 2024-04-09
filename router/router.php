<?php

use Laminas\Diactoros\ServerRequestFactory;
use League\OAuth2\Client\Token\AccessToken;
use League\Route\Router;
use League\Uri\Uri;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Symfony\Component\Filesystem\Path;
use TheNetworg\OAuth2\Client\Provider\Azure;
use Vendi\Theme\SSO\Azure\AzureApplicationUtility;
use Vendi\Theme\SsoRouter;

$request = ServerRequestFactory::fromGlobals($_SERVER, $_GET, $_POST, $_COOKIE, $_FILES);

$router = new Router;
$router
    ->get(
        SsoRouter::VENDI_PATH_SSO_LOOKUP,
        static function (ServerRequestInterface $request): ResponseInterface {

            if ($email = $request->getQueryParams()['email'] ?? null) {
                if (!$provider = (new AzureApplicationUtility)->getProviderForEmailAddress($email, $request)) {
                    throw new RuntimeException('No provider found for email address');
                }

                if (!$provider instanceof Azure) {
                    throw new RuntimeException('Invalid authentication provider for email address');
                }

                $provider->defaultEndPointVersion = Azure::ENDPOINT_VERSION_2_0;
                $baseGraphUri = $provider->getRootMicrosoftGraphUri(null);
                $provider->scope = 'openid profile email offline_access '.$baseGraphUri.'/User.Read';

                $authorizationUrl = $provider->getAuthorizationUrl(['scope' => $provider->scope, 'login_hint' => $email]);


                $_SESSION['aana.email'] = $email;
                $_SESSION['OAuth2.state'] = $provider->getState();

                return new Laminas\Diactoros\Response\RedirectResponse($authorizationUrl);
            }

            $lookupHtml = file_get_contents(Path::join(VENDI_CUSTOM_THEME_PATH, 'vendi-theme-parts', 'sso', 'enter-email.html'));

            $response = new Laminas\Diactoros\Response;
            $response->getBody()->write($lookupHtml);

            return $response;
        }
    );

$router
    ->get(
        SsoRouter::VENDI_PATH_SSO_CALLBACK,
        static function (ServerRequestInterface $request): ResponseInterface {
            if (!$email = $_SESSION['aana.email'] ?? null) {
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

//dd($_SERVER['REQUEST_URI']);

$uri = Uri::new($_SERVER['REQUEST_URI']);
if (str_starts_with($uri->getPath(), \Vendi\Theme\SsoRouter::VENDI_PATH_SSO_ROOT)) {
    $ret = (new \Vendi\Theme\SsoRouter)->getResponse();
}


dd($uri);
