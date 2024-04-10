<?php

namespace Vendi\Theme;

class SsoRouter
{
    public const VENDI_PATH_SSO_ROOT = '/vendi-auth/';

    public const VENDI_PATH_SSO_LOOKUP = self::VENDI_PATH_SSO_ROOT.'lookup/';
    public const VENDI_PATH_SSO_CALLBACK = self::VENDI_PATH_SSO_ROOT.'callback/';
}
