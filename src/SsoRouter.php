<?php

namespace Vendi\Theme;

use Laminas\Diactoros\Response;

class SsoRouter
{
    public const VENDI_PATH_SSO_ROOT = '/vendi-auth';

    public const VENDI_PATH_SSO_RELATIVE_LOOKUP = '/lookup';

    public function getResponse(): Response
    {
        dd($_SERVER);
        // Create an object representing the current HTTP request. We're
        // intentionally _not_ using anything from WordPress at this point
        // because WordPress tends to tweak the request for internal/security
        // reasons. We want to use the raw request.
        $request = Request::createFromGlobals();

        // In Symfony, the "context" is used to "fill in the blanks" for things
        // that are missing from the request. For example, sometimes the server
        // isn't aware of its own hostname or port, so a context can be used to
        // provide that information. In our case it doesn't matter, but we still
        // need to create a context object in order to use the URL matcher.
        $context = new RequestContext();
        $context->fromRequest($request);
    }
}
