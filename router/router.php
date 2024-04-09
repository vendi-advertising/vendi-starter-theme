<?php

use Laminas\Diactoros\ServerRequestFactory;
use League\Route\Router;
use League\Uri\Uri;

$request = ServerRequestFactory::fromGlobals(
    $_SERVER, $_GET, $_POST, $_COOKIE, $_FILES
);

$router = new Router;


//dd($_SERVER['REQUEST_URI']);

$uri = Uri::new($_SERVER['REQUEST_URI']);
if (str_starts_with($uri->getPath(), \Vendi\Theme\SsoRouter::VENDI_PATH_SSO_ROOT)) {
    $ret = (new \Vendi\Theme\SsoRouter)->getResponse();
}


dd($uri);
